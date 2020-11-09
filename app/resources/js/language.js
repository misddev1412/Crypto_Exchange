Vue.component('confirmation-dialog', require('../components/ConfirmationDialog').default);

new Vue({
    el: '#app',
    data: {
        languages: data.languages,
        selectedLanguage: data.selectedLanguage,
        searchPhrase: '',
        selectedKey: null,
        translations: [],
        filteredTranslations: [],
        newKeyErrorMsg: '',
        newKeyValueErrorMsg: '',
        confirmDialog: false,
        addNewKey: false,
        newKey: '',
        newKeyValue: ''
    },
    methods: {
        highlight(value) {
            if (!value) {
                return;
            }
            return value.replace(/:{1}[\w-]+/gi, function (match) {
                return '<mark>' + match + '</mark>';
            });
        },

        changeLanguage(language) {
            this.selectedLanguage = language;
            this.filteredTranslations = this.translations[language];
            this.searchPhrase = '';
            this.selectedKey = null;
            this.addNewKey = '';
        },

        save() {
            let request = {
                translations: this.translations
            };
            axios.put(data.routes.update, request).then(response => {
                flashBox(response.data.type, response.data.message);
            });
        },

        saveNewKey: function () {
            if (!this.newKey.length) {
                this.newKeyErrorMsg = data.newKeyError;
                return;
            }
            this.newKeyErrorMsg = '';

            if (!this.newKeyValue.length) {
                this.newKeyValueErrorMsg = data.newKeyValueError;
                return;
            }
            this.newKeyValueErrorMsg = '';

            _.forEach(this.languages, language => {
                this.translations[language][this.newKey] = this.newKeyValue;
            });

            this.save();
        },

        getTranslations() {
            axios.get(data.routes.getTranslations).then(response => {
                this.translations = response.data;
                this.filteredTranslations = this.translations[this.selectedLanguage];
            });
        },

        changeTranslations(key, event) {
            this.translations[this.selectedLanguage][key] = event.target.value;
            this.filteredTranslations[key] = event.target.value;
        },

        searchTranslations() {
            let filteredTranslations = {};
            if (this.searchPhrase.length > 0) {
                _.forEach(this.filteredTranslations, (value, translation) => {
                    if (translation && translation.toString().trim().toLowerCase().includes(this.searchPhrase.trim().toLowerCase())) {
                        filteredTranslations[translation] = this.filteredTranslations[translation];
                    }
                });
                this.filteredTranslations = filteredTranslations;
            } else {
                this.filteredTranslations = this.translations[this.selectedLanguage];
            }
        },
        sync() {
            axios.put(data.routes.sync).then(response => {
                flashBox(response.data.type, response.data.message);
                this.translations = response.data.translations;
                this.filteredTranslations = this.translations[this.selectedLanguage];
            });
        },

        add() {
            this.addNewKey = true;
        },

        remove() {
            this.confirmDialog = true;
        },

        confirmDelete() {
            this.confirmDialog = false;
            _.forEach(this.languages, lang => {
                this.translations[lang] = _.omit(this.translations[lang], [this.selectedKey]);
            });
            this.filteredTranslations = this.translations[this.selectedLanguage];
            this.selectedKey = null;
            this.save();
        },

        cancelDelete() {
            this.confirmDialog = false;
        }
    },
    mounted() {
        this.getTranslations();
    },
    filters: {
        uppercase: function (value) {
            if (!value) return '';
            value = value.toString();
            return value.toUpperCase();
        }
    }
});
