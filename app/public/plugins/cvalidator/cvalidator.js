"use strict";

(function ($) {
    $.fn.cValidate = function (options) {
        options = $.extend({
            onSuccess: '',
            loadingClass: 'is-loading',
            maxFileSizeLimit: 2048,
            rules: {},
            messages: {},
            attributes: {},
            customRules: {},
            commonFieldName: false,
            formGroupClass: 'form-group',
            messageClass: 'invalid-feedback',
            submitButtonClass: 'form-submission-button',
            messageOnSubmit: false,
            formSubmission: true
        }, options);

        let cValidate = this;
        let cValidateSingle = cValidate.attr('id');
        let formErrors = {};
        let data = {};
        let firstTime = true;
        let errorMessage = [];

        let htmlTags = [
            '<!--', '<!doctype', '<a', '<abbr', '<acronym', '<address', '<applet', '<area', '<article', '<aside', '<audio', '<b', '<base', '<basefont', '<bdi', '<bdo', '<big', '<blockquote', '<body', '<br', '<button', '<canvas', '<caption', '<center', '<cite', '<code', '<col', '<colgroup', '<datalist', '<dd', '<del', '<details', '<dfn', '<dir', '<div', '<dl', '<dt', '<em', '<embed', '<fieldset', '<figcaption', '<font', '<footer', '<form', '<frame', '<frameset', '<h1', '<h2', '<h3', '<h4', '<h5', '<h6', '<head', '<header', '<hr', '<html', '<i', '<iframe', '<img', '<input', '<ins', '<kbd', '<label', '<legend', '<li', '<link', '<main', '<map', '<mark', '<menu', '<menuitem', '<meta', '<meter', '<nav', '<noframes', '<noscript', '<object', '<ol', '<optgroup', '<option', '<output', '<p', '<param', '<picture', '<pre', '<progress', '<q', '<rp', '<rt', '<ruby', '<s', '<samp', '<script', '<section', '<select', '<small', '<source', '<span', '<strike', '<strong', '<style', '<sub', '<summary', '<sup', '<svg', '<table', '<tbody', '<td', '<template', '<textarea', '<tfoot', '<th', '<thead', '<time', '<title', '<tr', '<track', '<tt', '<u', '<ul', '<let', '<video', '<wbr'
        ];

        let cRules = {
            alpha: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                let valid = false;
                let regex = new RegExp(/[1|2|3|4|5|6|7|8|9|0|¶|¤|£|¢|¥|¿|¬|½|¼|«|»|¦|°|•|€|†|‡|™|©|®|¾|¸|¯|!|@|#|$|%|^|&|\*|_|-|=|\+|\(|\)|\{|\}|\[|\]|\'|\"|\\|\|\/|\?|.|>|,|<|`|~|§±\d|\s]/);
                if (regex.exec(value) === null) {
                    valid = true;
                }
                return {
                    result: valid,
                    attribute: data[field]['cvalName'],
                    rule: 'alpha',
                    others: {}
                }
            },
            alphaDash: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                let valid = false;
                let regex = new RegExp(/[¶|¤|£|¢|¥|¿|¬|½|¼|«|»|¦|°|•|€|†|‡|™|©|®|¾|¸|¯|!|@|#|$|%|^|&|\*|=|\+|\(|\)|\{|\}|\[|\]|\'|\"|\\|\|\/|\?|.|>|,|<|`|~|\s]/);
                if (regex.exec(value) === null) {
                    valid = true;
                }
                return {
                    result: valid,
                    attribute: data[field]['cvalName'],
                    rule: 'alphaDash',
                    others: {}
                }
            },
            alphaNum: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                let valid = false;
                let regex = new RegExp(/[¶|¤|£|¢|¥|¿|¬|½|¼|«|»|¦|°|•|€|†|‡|™|©|®|¾|¸|¯|!|@|#|$|%|^|&|\*|_|-|=|\+|\(|\)|\{|\}|\[|\]|\'|\"|\\|\|\/|\?|.|>|,|<|`|~|§±\d|\s]/);
                if (regex.exec(value) === null) {
                    valid = true;
                }
                return {
                    result: valid,
                    attribute: data[field]['cvalName'],
                    rule: 'alphaNum',
                    others: {}
                }
            },
            alphaSpace: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                let valid = false;
                let regex = new RegExp(/[1|2|3|4|5|6|7|8|9|0|¶|¤|£|¢|¥|¿|¬|½|¼|«|»|¦|°|•|€|†|‡|™|©|®|¾|¸|¯|!|@|#|$|%|^|&|\*|_|-|=|\+|\(|\)|\{|\}|\[|\]|\'|\"|\\|\|\/|\?|.|>|,|<|`|~|§±\d]/);
                if (regex.exec(value) === null) {
                    valid = true;
                }
                return {
                    result: valid,
                    attribute: data[field]['cvalName'],
                    rule: 'alphaSpace',
                    others: {}
                }
            },
            between: function (value, parameter, field, fieldType) {
                if (value === '' || (fieldType === 'file' && (!value.min || !value.max))) {
                    return {result: true};
                }

                if (fieldType === 'string') {
                    value = value.length;
                }

                let max = fieldType === 'file' ? value.max : value;
                let min = fieldType === 'file' ? value.min : value;
                return {
                    result: min >= +parameter[0] && max <= +parameter[1],
                    attribute: data[field]['cvalName'],
                    rule: 'between.' + fieldType,
                    others: {
                        min: parameter[0],
                        max: parameter[1]
                    }
                }
            },
            date: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                return {
                    result: checkDate(value),
                    attribute: data[field]['cvalName'],
                    rule: 'date',
                    others: {}
                }
            },
            dateFormat: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                let timeParts = value.split(' ');
                return {
                    result: timeParts.length === 2 && checkDate(timeParts[0]) && checkTime(timeParts[1]),
                    attribute: data[field]['cvalName'],
                    rule: 'dateTime',
                    others: {}
                }
            },
            dateTime: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                let timeParts = value.split(' ');
                return {
                    result: timeParts.length === 2 && checkDate(timeParts[0]) && checkTime(timeParts[1]),
                    attribute: data[field]['cvalName'],
                    rule: 'dateTime',
                    others: {}
                }
            },
            'decimalScale': function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }

                let result = true;
                if (!$.isNumeric(value)) {
                    result = false;
                }

                if (value < 0) {
                    value = value.replace('-', '');
                }

                let parts = value.toString().split('.');
                if (parts[0] === '' || parts[0].replace(/^0*/, '').length > parameter[0]) {
                    result = false;
                }

                if (parts.length === 2) {
                    let part1 = (/[0]*[1-9]+([0-9]*[1-9])*/).exec(parts[1]);
                    if (parts[1] === '' || (part1 && part1[0].length > parameter[1])) {
                        result = false;
                    }
                }

                return {
                    result: result,
                    attribute: data[field]['cvalName'],
                    rule: 'decimalScale',
                    others: {
                        other: `(${parameter[0]},${parameter[1]})`
                    }
                }
            },
            digitsOnly: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                let valid = false;
                let regex = new RegExp(/^\d+$/);
                if (regex.exec(value) === null) {
                    valid = true;
                }
                return {
                    result: valid,
                    attribute: data[field]['cvalName'],
                    rule: 'digitsOnly',
                    others: {}
                }
            },
            email: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                let regex = new RegExp(/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i);
                return {
                    result: regex.test(String(value)),
                    attribute: data[field]['cvalName'],
                    rule: 'email',
                    others: {}
                }
            },
            escapeInput: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                return {
                    result: !htmlTags.some(word => value.toLowerCase().includes(word)),
                    attribute: data[field]['cvalName'],
                    rule: 'escapeInput',
                    others: {}
                }
            },
            escapeFullText: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                let avoidable = ['<h1', '<h2', '<h3', '<h4', '<h5', '<h6', '<hr', '<article', '<section', '<video', '<audio', '<table', '<tr', '<td', '<thead', '<tfoot', '<footer', '<header', '<p', '<br', '<b', '<i', '<u', '<strong', '<ul', '<ol', '<dl', '<dt', '<li', '<div', '<sub', '<sup', '<span', '<img', '<a'];
                let diff = htmlTags.concat(avoidable).filter(function (e, i, array) {
                    // Check if the element is appearing only once
                    return array.indexOf(e) === array.lastIndexOf(e);
                });
                return {
                    result: !diff.some(word => value.toLowerCase().includes(word)),
                    attribute: data[field]['cvalName'],
                    rule: 'escapeFullText',
                    others: {}
                }
            },
            escapeText: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                let avoidable = ['<p', '<br', '<b', '<i', '<u', '<strong', '<ul', '<ol', '<li'];
                let diff = htmlTags.concat(avoidable).filter(function (e, i, array) {
                    // Check if the element is appearing only once
                    return array.indexOf(e) === array.lastIndexOf(e);
                });
                return {
                    result: !diff.some(word => value.toLowerCase().includes(word)),
                    attribute: data[field]['cvalName'],
                    rule: 'escapeText',
                    others: {}
                }
            },
            image: function (value, parameter, field, fieldType) {
                if (!value.ext || value.ext.length < 1) {
                    return {result: true};
                }
                let format = true;
                const types = [
                    'jpeg', 'png', 'jpg'
                ];

                for (let x in value.ext) {
                    if (types.indexOf(value.ext[x]) < 0) {
                        format = false;
                        break;
                    }
                }
                return {
                    result: format,
                    attribute: data[field]['cvalName'],
                    rule: 'image',
                    others: {}
                }
            },
            in: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                return {
                    result: parameter.indexOf(value) >= 0,
                    attribute: data[field]['cvalName'],
                    rule: 'in',
                    others: {}
                }
            },
            integer: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                let decimalParts = value.split('.').length;
                return {
                    result: decimalParts === 1 && $.isNumeric(value),
                    attribute: data[field]['cvalName'],
                    rule: fieldType,
                    others: {}
                }
            },
            max: function (value, parameter, field, fieldType) {
                if (fieldType === 'file') {
                    value = value.max
                }
                if (value === '') {
                    return {result: true};
                }
                if (fieldType === 'string') {
                    value = value.length;
                }

                return {
                    result: value <= +parameter[0],
                    attribute: data[field]['cvalName'],
                    rule: 'max.' + fieldType,
                    others: {
                        max: parameter[0]
                    }
                }
            },
            mimetypes: function (value, parameter, field, fieldType) {
                if (!value.ext || value.ext.length < 1) {
                    return {result: true};
                }
                let format = true;
                for (let x in value.ext) {
                    if (parameter.indexOf(value.ext[x]) < 0) {
                        format = false;
                        break;
                    }
                }
                return {
                    result: format,
                    attribute: data[field]['cvalName'],
                    rule: 'mimetypes',
                    others: {
                        values: parameter.join(', ')
                    }
                }
            },
            min: function (value, parameter, field, fieldType) {
                if (fieldType === 'file') {
                    value = value.min
                }
                let validation = {};
                if (value === '') {
                    return {result: true};
                }
                if (fieldType === 'string') {
                    value = value.length;
                }
                return {
                    result: value >= +parameter[0],
                    attribute: data[field]['cvalName'],
                    rule: 'min.' + fieldType,
                    others: {
                        min: parameter[0]
                    }
                }
            },
            notIn: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                return {
                    result: parameter.indexOf(value) < 0,
                    attribute: data[field]['cvalName'],
                    rule: 'notIn',
                    others: {}
                }
            },
            numeric: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }

                let validation = true;

                if (!$.isNumeric(value)) {
                    validation = false;
                }

                let decimalParts = value.split('.');
                let decimalLength = decimalParts.length;

                if (decimalLength > 2) {
                    validation = false;
                }

                if (decimalLength === 2 && decimalParts[1] === '') {
                    validation = false;
                }

                return {
                    result: validation,
                    attribute: data[field]['cvalName'],
                    rule: 'numeric',
                    others: {}
                }
            },
            required: function (value, parameter, field, fieldType) {
                return {
                    result: (fieldType === 'file' && value.ext.length > 0) || (fieldType != 'file' && value != ''),
                    attribute: data[field]['cvalName'],
                    rule: 'required',
                    others: {}
                }
            },
            requiredIf: function (value, parameter, field, fieldType) {
                let anotherFieldValue = $('#' + cValidateSingle + ' [name^="' + parameter[0] + '"]').val();

                let validation = true;
                let ruleName = 'requiredIf.field';
                let others = {
                    other: nameFixing(parameter[0])
                }
                if (parameter[1]) {
                    ruleName = 'requiredIf.fieldWithValue'
                    others.value = parameter[1]
                    if ((anotherFieldValue === parameter[1] && value === '')) {
                        validation = false;
                    }
                } else if (anotherFieldValue !== '' && value === '') {
                    validation = false
                }

                return {
                    result: validation,
                    attribute: data[field]['cvalName'],
                    rule: ruleName,
                    others: others,
                    relatedField: parameter[0]
                }
            },
            requiredWithout: function (value, parameter, field, fieldType) {
                let anotherFieldValue = $('#' + cValidateSingle + ' [name^="' + parameter[0] + '"]').val();
                return {
                    result: anotherFieldValue !== '' || value !== '',
                    attribute: data[field]['cvalName'],
                    rule: 'requiredWithout',
                    others: {
                        other: nameFixing(parameter[0])
                    },
                    relatedField: parameter[0]
                }
            },
            same: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                return {
                    result: value === $('#' + cValidateSingle + ' [name^="' + parameter[0] + '"]').val(),
                    attribute: data[field]['cvalName'],
                    rule: 'same',
                    others: {
                        other: nameFixing(parameter[0], parameter[1])
                    },
                    relatedField: parameter[0]
                }
            },
            strongPassword: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                let regex = new RegExp(/^.*(?=.{3,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\d\X]).*$/);
                if (value === '') {
                    return {result: true};
                }
                return {
                    result: regex.exec(value) != null,
                    attribute: data[field]['cvalName'],
                    rule: 'strongPassword',
                    others: {}
                }
            },
            time: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                return {
                    result: checkTime(),
                    attribute: data[field]['cvalName'],
                    rule: 'time',
                    others: {}
                }
            },
            url: function (value, parameter, field, fieldType) {
                if (value === '') {
                    return {result: true};
                }
                let pattern = new RegExp("((https?:\\/\\/(www\.))|(https?:\\/\\/)|(www\.))[-a-zA-Z0-9:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{2,}"); // fragment locator
                return {
                    result: pattern.test(value),
                    attribute: data[field]['cvalName'],
                    rule: 'url',
                    others: {}
                }
            }
        };

        function fieldNameMatch(name) {
            let fieldSplit = name.split('[');
            name = '';
            for (let x in fieldSplit) {
                let a = fieldSplit[x].split(']');
                if (a[0] === '') {
                    return false;
                }
                name = name + a[0];
                if (fieldSplit.length - 1 < x) {
                    name = name + '.';
                }
            }
            return name;
        }

        if (Object.keys(cRules).length > 0) {
            $.extend(cRules, options.customRules);
        }

        let currentActivity = false;

        function prepareData() {
            data = {}
            formErrors = {}
            for (let fieldName in options.rules) {
                let ruleLevels = fieldName.split('.');
                let regEx = '^';
                let targetFieldName = ruleLevels[0];
                let fieldNameMakingDone = false;
                for (let ruleLevel in ruleLevels) {
                    if (ruleLevels[ruleLevel] === '*') {
                        regEx = regEx + "\\[[a-zA-Z0-9_-]*\\]";

                        if (ruleLevel > 0 && !fieldNameMakingDone) {
                            fieldNameMakingDone = true;
                            targetFieldName = targetFieldName + '[';
                        }
                    } else {
                        regEx = regEx + ruleLevels[ruleLevel];
                        if (ruleLevel > 0 && !fieldNameMakingDone) {
                            targetFieldName = targetFieldName + '[' + ruleLevels[ruleLevel] + ']';
                        }
                    }
                }
                if (fieldNameMakingDone) {
                    targetFieldName = '[name^="' + targetFieldName + '"]';
                } else {
                    targetFieldName = '[name="' + targetFieldName + '"]';
                }
                if (!fieldNameMakingDone) {
                    regEx = regEx + "$";
                }

                regEx = new RegExp(regEx, "i");
                $('#' + cValidateSingle).find(targetFieldName).each(function () {
                    let actualFieldName = $(this).attr('name');
                    if (actualFieldName.match(regEx)) {
                        let fieldMatch = true;
                        let fieldSplit = actualFieldName.split('[');
                        let fieldSplitLength = fieldSplit.length;
                        let possibleFieldNameAttrs = false;
                        if (fieldSplitLength === 1) {
                            if (options.attributes[fieldSplit[0]]) {
                                possibleFieldNameAttrs = options.attributes[fieldSplit[0]];
                            }
                        } else {
                            let fieldStart = 1;
                            let fieldEnd = fieldSplitLength - 1;
                            if (fieldSplit[fieldSplitLength - 1] === ']') {
                                fieldEnd = fieldEnd - 1;
                            }
                            if (fieldEnd < fieldStart) {
                                if (options.attributes[fieldSplit[0] + '.*']) {
                                    possibleFieldNameAttrs = options.attributes[fieldSplit[0] + '.*'];
                                }
                            } else {
                                for (let x = fieldEnd; x >= fieldStart; x--) {
                                    for (let y in fieldSplit) {
                                        let z = fieldSplit[y].slice(0, -1);
                                        if (y > x) {
                                            possibleFieldNameAttrs = possibleFieldNameAttrs + '.*';
                                        } else {
                                            possibleFieldNameAttrs = possibleFieldNameAttrs + '.' + z;
                                        }
                                    }
                                    if (options.attributes[possibleFieldNameAttrs]) {
                                        possibleFieldNameAttrs = options.attributes[possibleFieldNameAttrs];
                                        break;
                                    } else {
                                        possibleFieldNameAttrs = false;
                                    }
                                }
                            }
                        }

                        if (ruleLevels.length === fieldSplit.length) {
                            for (let x in ruleLevels) {
                                if (
                                    ruleLevels[x] != '*' &&
                                    (
                                        (x === 0 && ruleLevels[x] != fieldSplit[x]) ||
                                        (x > 0 && ruleLevels[x] != fieldSplit[x] + ']')
                                    )
                                ) {
                                    fieldMatch = false;
                                    break;
                                }
                            }
                        } else {
                            fieldMatch = false;
                        }

                        if (fieldMatch) {
                            if (!data[actualFieldName]) {
                                data[actualFieldName] = {};
                            }
                            let splitedRules = options.rules[fieldName].split('|');
                            for (let x in splitedRules) {
                                let ruleNparam = singleSplitter(splitedRules[x]);
                                let actualRule = ruleNparam[0];
                                if (!data[actualFieldName][actualRule]) {
                                    data[actualFieldName][actualRule] = {};
                                    let actualParam = [];
                                    if (ruleNparam[1]) {
                                        actualParam = ruleNparam[1].split(',');
                                    }
                                    data[actualFieldName][actualRule]['param'] = actualParam;
                                }

                                if (options.messages[fieldName + '.' + actualRule]) {
                                    data[actualFieldName][actualRule]['message'] = options.attributes[fieldName]
                                } else {
                                    data[actualFieldName][actualRule]['message'] = false
                                }

                            }
                            if (possibleFieldNameAttrs) {
                                data[actualFieldName]['cvalName'] = possibleFieldNameAttrs
                            } else if (options.commonFieldName) {
                                data[actualFieldName]['cvalName'] = options.commonFieldName;
                            } else {
                                data[actualFieldName]['cvalName'] = titleCase(actualFieldName);
                            }
                        }
                    }
                })
            }
        }

        $(document).on('keyup change blur input focus ifChanged', ('#' + cValidateSingle + ' input'), function (event) {
            $(this).on('submit', function (event) {
                if (Object.keys(formErrors).length > 0 || !options.formSubmission) {
                    event.preventDefault();
                }
            });

            if ($(this).attr('type') !== 'submit') {
                firstTime = false;
                resetData($(this).attr('name'));
            }
        })

        $(document).on('keyup change blur input focus ifChanged', ('#' + cValidateSingle + ' select'), function (event) {
            $(this).on('submit', function (event) {
                if (Object.keys(formErrors).length > 0 || !options.formSubmission) {
                    event.preventDefault();
                }
            });
            firstTime = false;
            resetData($(this).attr('name'));
        });

        $(document).on('keyup change blur input focus ifChanged', ('#' + cValidateSingle + ' textarea'), function (event) {
            firstTime = false;
            resetData($(this).attr('name'));
        });

        if (options.messageOnSubmit) {
            $('#' + cValidateSingle).on('submit', function (event) {
                if( !options.formSubmission ) {
                    event.preventDefault();
                }

                if (Object.keys(formErrors).length > 0) {
                    event.preventDefault();
                    flashBox('error', errorMessage.join('<br>'));
                    resetData();
                }
            });
        }

        function resetData(indFieldName) {
            if (!currentActivity) {
                currentActivity = true;
                prepareData();
                eventInside(indFieldName)
                currentActivity = false;
            }
            return true;
        }

        resetData();

        function eventInside(indFieldName, preCalled) {
            errorMessage = [];
            for (let field in data) {
                let fieldDom = $('#' + cValidateSingle).find('[name^="' + field + '"]').eq(0);
                let inputType = $('#' + cValidateSingle).find('[name^="' + field + '"]').attr('type');
                let value = fieldDom.val();
                if (inputType) {
                    if (inputType === 'radio') {
                        let radioItem = $('#' + cValidateSingle).find('[name^="' + field + '"]:checked').eq(0);
                        value = radioItem.length === 1 ? radioItem.val() : '';
                    } else if (inputType === 'checkbox' && !fieldDom.is(':checked')) {
                        value = '';
                    } else if (inputType === 'file') {
                        value = {
                            min: false,
                            max: false,
                            ext: []
                        };
                        if (fieldDom[0].files && fieldDom[0].files.length > 0) {
                            for (let i = 0; i < fieldDom[0].files.length; i++) {
                                if (value.min === false) {
                                    value.min = fieldDom[0].files[i].size / 1000;
                                } else if (value.min > (fieldDom[0].files[i].size / 1000)) {
                                    value.min = fieldDom[0].files[i].size / 1000;
                                }
                                if (value.max === false) {
                                    value.max = fieldDom[0].files[i].size / 1000;
                                } else if (value.max < fieldDom[0].files[i].size / 1000) {
                                    value.max = fieldDom[0].files[i].size / 1000;
                                }
                                let fileExtension = fieldDom[0].files[i].name.split('.');
                                if (fileExtension.length > 1) {
                                    fileExtension = fileExtension[fileExtension.length - 1].toLowerCase();
                                } else {
                                    fileExtension = '';
                                }
                                value.ext.push(fileExtension);
                            }
                        }
                    }
                }
                let fieldType = 'string';
                if (data[field]['integer']) {
                    fieldType = 'integer';
                } else if (data[field]['numeric']) {
                    fieldType = 'numeric';
                } else if (inputType === 'file') {
                    fieldType = 'file';
                }
                let messageViewer = $('#' + cValidateSingle).find('[data-name="' + field + '"]');
                if (messageViewer.length <= 0) {
                    messageViewer = $('#' + cValidateSingle).find('[name="' + field + '"]').closest('.' + options.formGroupClass).find('.' + options.messageClass);
                }

                for (let rule in data[field]) {
                    if (rule != 'cvalName') {
                        let parameter = data[field][rule]['param']
                        let validation = cRules[rule](value, parameter, field, fieldType);

                        if (!validation.result) {
                            formErrors[rule] = 'error'
                            if (options.messageOnSubmit || (!firstTime && indFieldName && indFieldName === field)) {
                                let messageArray = validation.rule.split('.');
                                let message = cValMessages;
                                for (let x in messageArray) {
                                    message = message[messageArray[x]];
                                }
                                message = message.replace(":attribute", validation.attribute);
                                for (let x in validation.others) {
                                    message = message.replace(':' + x, validation.others[x]);
                                }
                                if (options.messageOnSubmit) {
                                    errorMessage.push(message);
                                } else {
                                    messageViewer.text(message);
                                }
                            }


                            break;
                            //set message here
                        } else if (indFieldName && indFieldName === field) {
                            messageViewer.text('');
                            if (validation.relatedField && !preCalled) {
                                eventInside(validation.relatedField, true)
                            }
                        }
                    }
                }
            }
            if (Object.keys(formErrors).length > 0 && !options.messageOnSubmit) {
                $('#' + cValidateSingle).find('.' + options.submitButtonClass).attr('disabled', 'disabled');
            } else {
                $('#' + cValidateSingle).find('.' + options.submitButtonClass).removeAttr('disabled');
            }
        }

        return {
            reFormat: function (inputArr) {
                if (inputArr) {
                    firstTime = false;
                    if (Array.isArray(inputArr)) {
                        inputArr.map(function (input) {
                            resetData(input);
                        });
                    } else {
                        resetData(inputArr);
                    }
                } else {
                    resetData();
                }
            },
            refreshDom: function () {
                let formEl = $('#' + cValidateSingle);
                formEl.html(formEl.html());
            },
            getErrorMessage: function () {
                return errorMessage;
            },
            setErrorMessage: function(messages){
                messages.forEach(function (key, value) {
                    $('#'+cValidateSingle).find('[name="'+key+'"]').closest('.' + options.formGroupClass).find('.' + options.messageClass).text(value[0])
                })
            },
            resetRules: function (newRules) {
                options.rules = newRules;
            },
            setRules: function (newRules) {
                options.rules = Object.assign(options.rules, newRules);
            },
            getRules: function () {
                return options.rules;
            },
        }

        function checkTime(timeValue) {
            let validation = true;
            let timeParts = timeValue.split(':');
            let regex = new RegExp(/^\d+$/);
            if (!timeParts[2]) {
                timeParts[2] = 0;
            }
            if (timeParts.length != 3 || regex.exec(timeParts[0]) === null || regex.exec(timeParts[1]) === null || regex.exec(timeParts[2]) === null) {
                validation = false;
            }
            if (validation) {
                timeParts[0] = parseInt(timeParts[0]);
                timeParts[1] = parseInt(timeParts[1]);
                timeParts[2] = parseInt(timeParts[2]);
                if (timeParts[0] < 0 || timeParts[0] > 23 || timeParts[1] < 0 || timeParts[1] > 59 || timeParts[2] < 0 || timeParts[2] > 59) {
                    validation = false;
                }
            }
            return validation
        }

        function checkDate(dateValue) {
            dateValue = dateValue.split('-');
            for (let i = 0; i < dateValue.length; i++) {
                let value = parseInt(dateValue[i], 10);
                !isNaN(value) && dateValue[i] >= 1 ? dateValue[i] = value : dateValue.splice(i, 1);
            }
            let month31 = [1, 3, 5, 7, 8, 10, 12];
            let month30 = [4, 6, 9, 11];
            if (
                dateValue.length != 3 ||
                (month31.indexOf(dateValue[1]) > -1 && dateValue[2] > 31) ||
                (month30.indexOf(dateValue[1]) > -1 && dateValue[2] > 30) ||
                (dateValue[0] % 4 === 0 && dateValue[1] === 2 && dateValue[2] > 29) ||
                (dateValue[0] % 4 > 0 && dateValue[1] === 2 && dateValue[2] > 28)
            ) {
                return false;
            }
            return true;
        }

        function titleCase(name) {
            return name.replace(/_|-/g, " ").replace(/([A-Z])/g, " $1").toLowerCase();
        }

        function singleSplitter(rule) {
            rule = rule.split(/\:(.*)/);
            rule = rule.filter(el => {
                return el != null && el != '';
            });
            return rule;
        }

        function nameFixing(name, optionalName) {
            let output = titleCase(name);
            if (optionalName) {
                output = optionalName;
            } else {
                let fieldNameMatched = fieldNameMatch(name);
                if (fieldNameMatched && options.attributes[fieldNameMatched]) {
                    output = options.attributes[fieldNameMatched];
                }
            }
            return output;
        }
    };
})(jQuery);
