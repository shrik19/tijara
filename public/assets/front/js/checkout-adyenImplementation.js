const clientKey = document.getElementById("clientKey").innerHTML;
const type = document.getElementById("type").innerHTML;
const orderId = document.getElementById("orderId").innerHTML;
const paymentAmount = document.getElementById("paymentAmount").innerHTML;

async function initCheckout() {
  try {
    const paymentMethodsResponse = await callServer("/getSwishPaymentMethods/");
    
    setTimeout(function(){ 
      document.getElementsByClassName('adyen-checkout__button')[0].style.visibility = 'hidden';
      document.querySelector(".adyen-checkout__button").click(); }, 100);
    const configuration = {
      paymentMethodsResponse: filterUnimplemented(paymentMethodsResponse),
      clientKey,
      locale: "sv_SE",
      environment: "test",
      showPayButton: true,
      paymentMethodsConfiguration: {
        ideal: {
          showImage: true,
        },
        card: {
          hasHolderName: true,
          holderNameRequired: true,
          name: "Credit or debit card",
          amount: {
            value: paymentAmount,
            currency: "SEK",
          },
        },
      },
      onSubmit: (state, component) => {
        if (state.isValid) {
          handleSubmission(state, component, "/swishInitiatePayment/");
        }
      },
      onAdditionalDetails: (state, component) => {
        handleSubmission(state, component, "/checkoutSubmitAdditionalDetails/");
      },
    };

    const checkout = new AdyenCheckout(configuration);
    checkout.create(type).mount(document.getElementById(type));
  
  } catch (error) {
    console.error(error);
    alert("Error occurred. Look at console for details");
  }
}

function filterUnimplemented(pm) {
  pm.paymentMethods = pm.paymentMethods.filter((it) =>
    [
      "scheme",
      "ideal",
      "dotpay",
      "giropay",
      "sepadirectdebit",
      "directEbanking",
      "ach",
      "alipay",
    ].includes(it.type)
  );
  return pm;
}

// Event handlers called when the shopper selects the pay button,
// or when additional information is required to complete the payment
async function handleSubmission(state, component, url) {
  try {
    const res = await callServer(url, state.data);
    
    //handleSubmission(state, component, "/api/handleShopperRedirect");
    handleServerResponse(res, component);
  } catch (error) {
    console.error(error);
    alert("Error occurred. Look at console for details");
  }
}

// Calls your server endpoints
async function callServer(url, data) {
  const res = await fetch(url, {
    method: "POST",
    body: data ? JSON.stringify(data) : "",
    headers: {
      "Content-Type": "application/json",
    },
  });

  return await res.json();
}

// Handles responses sent from your server to the client
function handleServerResponse(res, component) {
  if (res.action) {
    component.handleAction(res.action);
  } else {
    console.log(res);
    
    switch (res.resultCode) {
      case "Authorised":
        window.location.href = "/checkout-swish-callback?status=success";
        break;
      case "Pending":
      case "Received":
        window.location.href = "/checkout-swish-callback?status=pending";
        break;
      case "Refused":
        window.location.href = "/checkout-swish-callback?status=failed";
        break;
      default:
        window.location.href = "/checkout-swish-callback?status=error";
        break;
    }
  }
}

initCheckout();