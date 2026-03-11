// Note: Include this script globally 

// Global variable declaration
var _gblPositive = [];
var _gblNegative = [];

// Returns TRUE if keycode/charcode is numeric
function _gblIsNumeric5dec(e) {
    var charCode = (e.which) ? e.which : event.keyCode;
    return (charCode >= 48 && charCode <= 57 || charCode == 13) ? true : false;
};

// Returns a formatted amount based on positive and negative values
function _gblConcatAmount5dec(positive, negative) {
    var _positive = (positive.length > 0) ? positive.join('') : '0';
    var _negative = '00000';

    if (negative.length == 5) {
        _negative = negative.join('');
    } else if (negative.length == 2) {
        _negative = negative.join('') + '0';
    } else if (negative.length == 3) {
        _negative = negative.join('') + '00';
    } else if (negative.length == 4) {
        _negative = negative.join('') + '000';
    } else {
        _negative = negative.join('') + '0000';
    }

    var _amount = parseFloat(_positive + '.' + _negative).toLocaleString(undefined, {
        minimumFractionDigits: 5,
        maximumFractionDigits: 5
    });
    return _amount;
};

// Keydown event listener
function _gblOnKeyDown5dec(e) {
    var charCode = (e.which) ? e.which : event.keyCode;

    // Period/Dot keypress
    if (charCode == 190) {
        this.classList.add('dot-enabled');
        this.classList.remove('focused');
        _gblNegative = [];
        return;
    }

    // Delete keypress
    if (charCode == 46 || charCode == 8) {
        e.target.value = '0.00000';
        this.classList.remove('dot-enabled');
        _gblNegative = [];
        _gblPositive = [];
        return;
    }

    // Backspace keypress
    // if (charCode == 8) {
    //     return e.preventDefault();
    // }

    var dotEnabled = this.classList.contains('dot-enabled');
    var focused = this.classList.contains('focused');

    if (!dotEnabled && focused) {
        _gblPositive = [];
        this.classList.remove('focused');
        return;
    }
};

// Keypress event listener
function _gblOnKeyPress5dec(e) {
    e.preventDefault();

    if (_gblIsNumeric5dec(e)) {
        var dotEnabled = this.classList.contains('dot-enabled');
        if (!dotEnabled) {
            _gblPositive.push(e.key);
        }

        if (dotEnabled && _gblNegative.length <= 5) {
            _gblNegative.push(e.key);
        }

        this.value = _gblConcatAmount5dec(_gblPositive, _gblNegative);
    }
    return;
};

// Focus event listener
function _gblOnFocus5dec(e) {
    this.classList.add('focused');
    this.classList.remove('dot-enabled');
    _gblPositive = Array.from(e.target.value.split('.')[0].replace(/,/g, ''));
    _gblNegative = Array.from(e.target.value.split('.')[1].replace(/,/g, ''));
    return;
};

// Blur event listener
function _gblOnBlur5dec(e) {
    this.classList.remove('focused');
    this.classList.remove('dot-enabled');
    return;
};

/**
 * param: className
 * Gets all input fields with numeric classes and binds the event listeners
 * CALL this function in every module of the program that requires numeric key binding 
 */
function _gblBindNumericClasses5dec(className) {
    var inputNumFields = document.querySelectorAll('.' + className);
    inputNumFields.forEach(input => {
        input.addEventListener('keydown', _gblOnKeyDown5dec);
        input.addEventListener('keypress', _gblOnKeyPress5dec);
        input.addEventListener('focus', _gblOnFocus5dec);
        input.addEventListener('blur', _gblOnBlur5dec);
    });
};