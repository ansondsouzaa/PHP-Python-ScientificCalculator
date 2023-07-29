// adding values in expressions
// Function to append operators, numbers, and functions to the expression
function appendToExpression(operator) {
  var expressionInput = document.getElementById("expression");

  // If input is empty and operator is added, add 0 in the input field to proceed
  if (expressionInput.value.trim() === "" && /[+x÷%]/.test(operator)) {
    expressionInput.value = "0";
  }

  // Disallow consecutive occurrences of "+", "-", "x", "÷", and "%" operators
  if (/[+x÷%]{2,}$/.test(expressionInput.value) && /[+x÷%]$/.test(operator)) {
    return;
  }

  // Prevent "-" and "+" directly before "÷" and "x"
  if (/[-+]$/.test(expressionInput.value) && /[÷x]$/.test(operator)) {
    return;
  }

  // Add auto-closing parentheses
  var countOpen = (expressionInput.value.match(/\(/g) || []).length;
  var countClose = (expressionInput.value.match(/\)/g) || []).length;
  if (operator === "(") {
    if (countOpen > countClose) {
      operator = ")";
    }
  }

  // If the user tries to close a parenthesis without opening one, open one instead
  if (operator === ")" && countClose >= countOpen) {
    operator = "(";
  }

  // Replace 0 with the operator value if the operator is not ".", "x", "÷", or "%"
  if (/[^.+x÷%]/.test(operator) && expressionInput.value === "0") {
    expressionInput.value = operator;
  } else {
    expressionInput.value += operator;
  }

  // Handle "%" operator
  if (operator === "%") {
    // Check if the expression ends with "%" and the new entry is a number
    if (/%$/.test(expressionInput.value) && /\d/.test(operator)) {
      operator = "x" + operator; // Add "x" before the number
    }
  }

  // expressionInput.value += operator;
  expressionInput.focus();
}

// Toggling between modes
function toggleMode(mode) {
  var modeButtons = document.querySelectorAll(".mode-buttons button");
  modeButtons.forEach(function (button) {
    if (button.getAttribute("data-mode") === mode) {
      button.classList.add("active");
    } else {
      button.classList.remove("active");
    }
  });

  var modeInput = document.querySelector('input[name="mode"]');
  modeInput.value = mode;
}

// Reset to 0 when AC is clicked
function clearExpression() {
  var expressionInput = document.getElementById("expression");
  expressionInput.value = "0";
  expressionInput.focus();
}

// Clear last entry
function clearLastEntry() {
  var expressionInput = document.getElementById("expression");
  var currentValue = expressionInput.value.trim();

  // Regular expression to match the last input (operator, trigonometric function, number, constant, or closing parenthesis)
  var regex =
    /(\b(sin|cos|tan|sinh|cosh|tanh|log|[+\-x÷])\(|\d+|\)|-|()|x|π|e|²|\b([+\-x÷]))\s*$/;
  var match = currentValue.match(regex);

  if (match) {
    var lastEntry = match[0];
    var lastChar = lastEntry.slice(-1); // Get the last character

    // Check if the last character is numeric, a constant, or a closing parenthesis, then remove one character
    if (
      !isNaN(parseFloat(lastChar)) ||
      lastChar === ")" ||
      lastChar === "π" ||
      lastChar === "e"
    ) {
      expressionInput.value = currentValue.slice(0, -1).trim();
    } else {
      // If the last character is not numeric, a constant, or a closing parenthesis, remove the entire entry
      expressionInput.value = currentValue.slice(0, -lastEntry.length).trim();
    }

    expressionInput.focus();
  }
  // If the input field is empty, set it to "0"
  if (expressionInput.value.trim() === "") {
    expressionInput.value = "0";
  }
}

// Auto closing parenthesis
function autoCloseParentheses() {
  var expressionInput = document.getElementById("expression");
  var countOpen = (expressionInput.value.match(/\(/g) || []).length;
  var countClose = (expressionInput.value.match(/\)/g) || []).length;

  // Add missing closing parentheses at the end of the expression
  if (countOpen > countClose) {
    expressionInput.value += ")".repeat(countOpen - countClose);
  }
}

document
  .getElementById("calculateButton")
  .addEventListener("click", function () {
    autoCloseParentheses();
  });

// Add event listener to the form submission
document
  .getElementById("calculatorForm")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission behavior
    var expressionInput = document.getElementById("expression");
    var countOpen = (expressionInput.value.match(/\(/g) || []).length;
    var countClose = (expressionInput.value.match(/\)/g) || []).length;

    // Add missing closing parentheses at the end of the expression
    if (countOpen > countClose) {
      expressionInput.value += ")".repeat(countOpen - countClose);
    }

    // Now, you can submit the form with the updated expression
    event.target.submit();
  });
