//! Đối tượng
function Validator(options) {

    var selectorRules = {};

    //* Hàm thực hiện validate
    function validate(inputElement, rule) {


        var errorElement = inputElement.parentElement.querySelector(options.errorSelector);
        var errorMessage;

        //* Lấy ra các rule của selector
        var rules = selectorRules[rule.selector]

        //* Lặp qua từng rule & ktra
        //* Nếu có lỗi thì dừng việc kiểm tra
        for (var i = 0; i < rules.length; i++) {
            errorMessage = rules[i](inputElement.value);
            if (errorMessage) break;
        }

        if (errorMessage) {
            errorElement.innerText = errorMessage;
            inputElement.parentElement.classList.add('invalid');
        } else {
            errorElement.innerText = '';
            inputElement.parentElement.classList.remove('invalid');
        }

        return !errorMessage;
    }

    //* Lấy element của form cần validate
    var formElement = document.querySelector(options.form);

    if (formElement) {

        //* Khi submit form
        formElement.onsubmit = function (e) {
            e.preventDefault();

            var isFormValid = true;


            //* Lặp qua từng rule và validate
            options.rules.forEach(rule => {
                var inputElement = formElement.querySelector(rule.selector);
                var isValid = validate(inputElement, rule)
                if (!isValid) {
                    isFormValid = false;
                }
            })



            if (isFormValid) {
                //* Trường hợp submit với js
                if (typeof options.onSubmit === 'function') {

                    var enableInputs = formElement.querySelectorAll('[name]')
                    var formValues = Array.from(enableInputs).reduce(function (values, input) {
                        return (values[input.name] = input.value) && values;
                    }, {})

                    options.onSubmit(formValues);
                }
                //* Trường hợp submit với hành vi mặc định
                else {
                    formElement.submit();
                }
            }
        }

        //* Lặp qua mỗi rules và xử lý
        options.rules.forEach(rule => {

            //* Lưu lại các rule cho mỗi input'

            if (Array.isArray(selectorRules[rule.selector])) {
                selectorRules[rule.selector].push(rule.test);
            } else {
                selectorRules[rule.selector] = [rule.test];
            }


            var inputElement = formElement.querySelector(rule.selector);

            if (inputElement) {
                //* Xử lý trường hợp blur khỏi input
                inputElement.onblur = function () {
                    validate(inputElement, rule)
                }

                //* Xử lý mỗi khi người dùng nhập vào input
                inputElement.oninput = function () {
                    var errorElement = inputElement.parentElement.querySelector(options.errorSelector);
                    errorElement.innerText = '';
                    inputElement.parentElement.classList.remove('invalid');
                }
            }
        });
    }
}


//! Định nghĩa các rules
//* Nguyen tắc các rules
// 1. Khi có lỗi, trả ra message lỗi
// 2. khi hợp lệ, k trả ra cái gì (undefined)


Validator.isRequired = function (selector, message) {
    return {
        selector: selector,
        test: function (value) {
            return value.trim() ? undefined : message || 'Vui long nhap truong nay'
        }
    }
}


Validator.isEmail = function (selector, message) {
    return {
        selector: selector,
        test: function (value) {
            var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

            return regex.test(value) ? undefined : message || ' Truong nay phai la email'
        }
    }
}


Validator.minLength = function (selector, min, message) {
    return {
        selector: selector,
        test: function (value) {
            return value.length >= min ? undefined : message || `Vui long nhap toi thieu ${min} ky tu`
        }
    }
}

Validator.isConfirmed = function (selector, getConfirmValue, message) {
    return {
        selector: selector,
        test: function (value) {
            return value === getConfirmValue() ? undefined : message || "Giá trị nhập vào không chính xác"
        }
    }
}