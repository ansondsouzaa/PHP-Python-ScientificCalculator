import math
import sys

def format_result(result):
    if len(result) > 20:
        # Convert the result to scientific notation with 2 decimal places
        return "{:.10e}".format(float(result))
    else:
        return result
    
def scientific_calculator(expression, mode):
    try:
        expression = expression.replace('x', '*')  # Replace 'x' with '*'
        expression = expression.replace('÷', '/')  # Replace '÷' with '/'
        expression = expression.replace('√', 'sqrt(')  # Replace '√' with 'sqrt('
        expression = expression.replace('²', '**2')  # Replace '²' with '**2' for squaring
        expression = expression.replace('π', 'pi()')  # Replace 'π' with 'pi()'
        expression = expression.replace('e', 'e()')  # Replace 'e' with 'e()' for squaring

        # If there is an opening parenthesis but no closing parenthesis, adding a closing parenthesis
        num_open_parenthesis = expression.count('(')
        num_close_parenthesis = expression.count(')')
        if num_open_parenthesis > num_close_parenthesis:
            expression += ')' * (num_open_parenthesis - num_close_parenthesis)

        # Converting degrees to radians for trigonometric functions if mode set to DEG
        if mode == "DEG":
            trig_functions = ["sin", "cos", "tan", "sinh", "cosh", "tanh"]
            for func in trig_functions:
                # If there is an opening parenthesis but no closing parenthesis, add a closing parenthesis
                num_open_parenthesis = expression.count('(')
                num_close_parenthesis = expression.count(')')
                if num_open_parenthesis > num_close_parenthesis:
                    expression += ')' * (num_open_parenthesis - num_close_parenthesis)
                expression = expression.replace(f'{func}(', f'{func}(math.radians(')

        # Explicitly import sin, cos, tan, sinh, cosh, tanh, sqrt, and factorial functions from the math module
        sin = math.sin
        cos = math.cos
        tan = math.tan
        sinh = math.sinh
        cosh = math.cosh
        tanh = math.tanh
        sqrt = math.sqrt
        log = math.log10
        factorial = math.factorial

        result = eval(expression)
        return format_result(str(result))
    except Exception as e:
        return "Error: " + str(e)

def pi():
    return math.pi

def e():
    return math.e

def calculate_percentage(expression):
    try:
        expression = expression.replace('%', '/100')  # Replace '%' with '/100'
        result = eval(expression)
        return str(result)
    except Exception as e:
        return "Error: " + str(e)

if __name__ == "__main__":
    expression = sys.argv[1]
    mode = sys.argv[2]
    if '%' in expression:
        # Calculate the expression with percentage first
        expression = calculate_percentage(expression)
    result = scientific_calculator(expression, mode)
    print(result)
