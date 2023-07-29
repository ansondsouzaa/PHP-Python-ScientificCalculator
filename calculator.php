<?php
require('db.php');
include("auth_session.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>PHP & Python Scientific Calculator</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <p>
            <em><--- </em>
                    <a href="dashboard.php">dashboard</a>
        </p>
        <h1 class="center">The Scientific Calculator</h1>
        <div class="calculator">
            <form action="" method="post">
                <!-- main input where expression is present -->
                <input type="text" name="expression" id="expression" class="calc-input" readonly
                    value="<?php echo isset($_POST['expression']) ? htmlspecialchars($_POST['expression']) : '0'; ?>">
                <!-- php start -->
                <?php
                function isValidExpression($expression)
                {
                    // Disallow consecutive occurrences of "+", "-", "x", and "÷" operators
                    if (preg_match('/[*+x÷-]{2,}/', $expression)) {
                        return false;
                    }
                    // Prevent "-" and "+" directly before "÷" and "x"
                    if (preg_match('/[+-][÷x]/', $expression)) {
                        return false;
                    }
                    // Check if expression ends with an operator
                    if (preg_match('/[+x÷-]$/', $expression)) {
                        return false;
                    }
                    return true;
                }

                if (isset($_POST['expression'])) {
                    $expression = $_POST['expression'];
                    // Checking if the expression is valid before submitting
                    if (isValidExpression($expression)) {
                        $mode = isset($_POST['mode']) ? $_POST['mode'] : 'RAD';
                        $result = '';
                        // Using the subprocess module to run the Python script and get the result
                        if (substr(php_uname(), 0, 7) == "Windows") {
                            // For Windows OS
                            $command = "python calculate.py \"$expression\" \"$mode\"";
                            $result = shell_exec($command);
                        } else {
                            // For Linux/Mac OS
                            $command = "python3 calculate.py \"$expression\" \"$mode\"";
                            $result = shell_exec($command);
                        }
                        echo '<div class="result">';
                        echo '<pre> Ans:<span class="color-green">' . htmlspecialchars($result) . '</span></pre>';
                        echo '</div>';
                    } else {
                        echo '<div class="result">';
                        echo '<p>Invalid expression. Please check your input.</p>';
                        echo '</div>';
                    }
                }
                ?>
                <div class="flex">
                    <!-- Brackets -->
                    <button type="button" class="op-button" onclick="appendToExpression('(')">(</button>
                    <button type="button" class="op-button" onclick="appendToExpression(')')">)</button>
                    <!-- Action Buttons -->
                    <button type="button" class="act-button" id="acButton" onclick="clearExpression()">AC</button>
                    <button type="button" class="act-button" id="ceButton" onclick="clearLastEntry()">CE</button>
                    <!-- Input numbers -->
                    <button type="button" class="num-button" onclick="appendToExpression('7')">7</button>
                    <button type="button" class="num-button" onclick="appendToExpression('8')">8</button>
                    <button type="button" class="num-button" onclick="appendToExpression('9')">9</button>
                    <!-- Opeartor Button divide -->
                    <button type="button" class="op-button" onclick="appendToExpression('÷')">÷</button>
                    <!-- Input numbers -->
                    <button type="button" class="num-button" onclick="appendToExpression('4')">4</button>
                    <button type="button" class="num-button" onclick="appendToExpression('5')">5</button>
                    <button type="button" class="num-button" onclick="appendToExpression('6')">6</button>
                    <!-- Opeartor Button multiply -->
                    <button type="button" class="op-button" onclick="appendToExpression('x')">x</button>
                    <!-- Input numbers -->
                    <button type="button" class="num-button" onclick="appendToExpression('1')">1</button>
                    <button type="button" class="num-button" onclick="appendToExpression('2')">2</button>
                    <button type="button" class="num-button" onclick="appendToExpression('3')">3</button>
                    <!-- Opeartor Button minus -->
                    <button type="button" class="op-button" onclick="appendToExpression('-')">—</button>
                    <!-- Input numbers -->
                    <button type="button" class="num-button" onclick="appendToExpression('0')">0</button>
                    <button type="button" class="num-button" onclick="appendToExpression('.')">.</button>
                    <!-- MODE select -->
                    <input type="hidden" name="mode"
                        value="<?php echo isset($_POST['mode']) ? $_POST['mode'] : 'RAD'; ?>">
                    <!-- Submit button -->
                    <input type="submit" class="calc-button" id="calculateButton" value="=">
                    <!-- Opeartor Button plus-->
                    <button type="button" class="op-button" onclick="appendToExpression('+')">+</button>
                </div>
                <div class="flex">
                    <!-- Percentage Button -->
                    <button type="button" class="op-button" onclick="appendToExpression('%')">%</button>
                    <button type="button" class="fun-button" onclick="appendToExpression('√(')">√</button>
                    <button type="button" class="fun-button" onclick="appendToExpression('²')">x²</button>
                    <button type="button" class="fun-button" onclick="appendToExpression('factorial(')">x!</button>
                    <button type="button" class="fun-button" onclick="appendToExpression('π')">π</button>
                    <button type="button" class="fun-button" onclick="appendToExpression('e')">e</button>
                    <!-- Mode Buttons -->
                    <div class="mode-buttons">
                        <button type="button" onclick="toggleMode('RAD')" data-mode="RAD" <?php if (!isset($_POST['mode']) || $_POST['mode'] === 'RAD') {
                            echo 'class="active"';
                        } ?>>Rad</button>
                        |
                        <button type="button" onclick="toggleMode('DEG')" data-mode="DEG" <?php if (isset($_POST['mode']) && $_POST['mode'] === 'DEG') {
                            echo 'class="active"';
                        } ?>>Deg</button>
                    </div>
                    <!-- Function Buttons -->
                    <button type="button" class="fun-button" onclick="appendToExpression('log(')">log</button>
                    <button type="button" class="fun-button" onclick="appendToExpression('sin(')">sin</button>
                    <button type="button" class="fun-button" onclick="appendToExpression('cos(')">cos</button>
                    <button type="button" class="fun-button" onclick="appendToExpression('tan(')">tan</button>
                    <!-- <button type="button" class="fun-button" onclick="appendToExpression('sinh(')">sinh</button>
                    <button type="button" class="fun-button" onclick="appendToExpression('cosh(')">cosh</button>
                    <button type="button" class="fun-button" onclick="appendToExpression('tanh(')">tanh</button> -->
                </div>
            </form>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>

</html>