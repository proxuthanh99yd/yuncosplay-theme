document.addEventListener('alpine:init', () => {
    // Global toast component
    Alpine.data('globalToast', () => ({
        toast: {
            visible: false,
            message: '',
            type: 'error'
        },
        timeoutId: null,
        init() {
            // Make global toast accessible
            window.globalToast = this;
        },

        showToast(message, type = 'error') {
            console.log('showToast called:', message, type);
            console.log('globalToast instance:', this);
            this.toast.message = message;
            this.toast.type = type;
            this.toast.visible = true;
            console.log('toast state after show:', this.toast);
            if (this.timeoutId) {
                clearTimeout(this.timeoutId)
            }
            this.timeoutId = setTimeout(() => {
                this.hideToast();
            }, 5000);
        },

        hideToast() {
            this.toast.visible = false;
        }
    }));
    Alpine.data('destinationSelector', () => ({
        // STATE
        isOpen: false,
        selectedDestinations: [],

        init() {
            // Sync with form checkboxes on page load
            const checkboxes = this.$el.querySelectorAll('input[name="destination[]"]:checked');
            checkboxes.forEach(checkbox => {
                if (!this.selectedDestinations.includes(checkbox.value)) {
                    this.selectedDestinations.push(checkbox.value);
                }
            });

            // Sync with parent form data
            if (window.contactForm) {
                window.contactForm.formData.destinations = this.selectedDestinations;
            }

            // Watch for changes to validate and sync immediately
            this.$watch('selectedDestinations', () => {
                this.validateAndSync();
            });
        },

        validateAndSync() {
            // Sync to parent form immediately for select components
            // Use timeout to ensure contactForm is initialized
            setTimeout(() => {
                if (window.contactForm) {
                    // Mark that user has interacted with the form
                    window.contactForm.hasBeenInteracted = true;
                    // Use Object.assign to trigger Alpine reactivity
                    window.contactForm.formData = Object.assign({}, window.contactForm.formData, {
                        destinations: this.selectedDestinations
                    });
                    // Trigger validation in parent form
                    window.contactForm.validateField('destinations');
                }
            }, 0);
        },

        // METHODS
        toggleDropdown() {
            this.isOpen = !this.isOpen;
        },

        closeDropdown() {
            this.isOpen = false;
        },

        toggleDestination(destination) {
            if (this.selectedDestinations.includes(destination)) {
                this.selectedDestinations = this.selectedDestinations.filter(d => d !== destination);
            } else {
                this.selectedDestinations.push(destination);
            }
        },

        getButtonText() {
            if (!this.selectedDestinations.length) {
                return 'Select destinations';
            }
            return this.selectedDestinations.join(', ');
        },

        closeDropdown() {
            if (this.isOpen) {
                this.isOpen = false;
                // Enable scroll directly
                document.documentElement.style.overflow = '';
                console.log('Scroll enabled by destinationDrawer');
            }
        }
    }));
    Alpine.data('destinationDrawer', () => ({
        // STATE
        isOpen: false,
        selectedDestinations: [],
        searchTerm: '',

        init() {
            // Make instance globally accessible for reset
            window.destinationDrawerInstance = this;
            // Sync with form checkboxes on page load
            const checkboxes = this.$el.querySelectorAll('input[name="destination[]"]:checked');
            checkboxes.forEach(checkbox => {
                if (!this.selectedDestinations.includes(checkbox.value)) {
                    this.selectedDestinations.push(checkbox.value);
                }
            });

            // Sync with parent form data
            if (window.contactForm) {
                window.contactForm.formData.destinations = this.selectedDestinations;
            }

            // Watch for changes to validate and sync immediately
            this.$watch('selectedDestinations', () => {
                this.validateAndSync();
            });
        },

        validateAndSync() {
            // Sync to parent form immediately for select components
            // Use timeout to ensure contactForm is initialized
            setTimeout(() => {
                if (window.contactForm) {
                    // Mark that user has interacted with the form
                    window.contactForm.hasBeenInteracted = true;
                    // Use Object.assign to trigger Alpine reactivity
                    window.contactForm.formData = Object.assign({}, window.contactForm.formData, {
                        destinations: this.selectedDestinations
                    });
                    // Trigger validation in parent form
                    window.contactForm.validateField('destinations');
                }
            }, 0);
        },

        // METHODS
        toggleDropdown() {
            if (!this.isOpen) {
                // Disable scroll directly
                document.documentElement.style.overflow = 'hidden';
                console.log('Scroll disabled by destinationDrawer');
            }
            this.isOpen = !this.isOpen;
        },

        closeDropdown() {
            if (this.isOpen) {
                // Enable scroll via App class
                // Enable scroll directly
                document.documentElement.style.overflow = '';
                console.log(`Scroll enabled by ${this.fieldName}Select`);
                this.isOpen = false;
            }
        },

        toggleDestination(destination) {
            if (this.selectedDestinations.includes(destination)) {
                this.selectedDestinations = this.selectedDestinations.filter(d => d !== destination);
            } else {
                this.selectedDestinations.push(destination);
            }
        },

        getButtonText() {
            if (!this.selectedDestinations.length) {
                return 'Select destinations';
            }
            return this.selectedDestinations.join(', ');
        },

        // COMPUTED
        isVisible(destinationName) {
            if (!this.searchTerm) return true;
            return destinationName.toLowerCase().includes(this.searchTerm.toLowerCase());
        }
    }));

    const handleSelect = (fieldName) => ({
        // STATE
        isOpen: false,
        selected: '',
        fieldName: fieldName,
        searchTerm: '',
        errors: {},

        // METHODS
        toggleDropdown() {
            this.isOpen = !this.isOpen;
        },

        closeDropdown() {
            this.isOpen = false;
        },

        validateAndSync() {
            // Update parent form data immediately for select components
            // Use timeout to ensure contactForm is initialized
            setTimeout(() => {
                if (window.contactForm) {
                    // Direct assignment to ensure Alpine reactivity
                    window.contactForm.formData[this.fieldName] = this.selected;
                    // Trigger validation in parent form
                    window.contactForm.validateField(this.fieldName);
                }
            }, 0);
        },

        select(value) {
            this.selected = value;
            this.closeDropdown();
            this.validateAndSync();
        },

        clearSelection() {
            this.selected = '';
            this.validateAndSync();
        },

        closeDropdown() {
            if (this.isOpen) {
                this.isOpen = false;
                // Enable scroll directly
                document.documentElement.style.overflow = '';
                console.log(`Scroll enabled by ${this.fieldName}Select`);
            }
        },

        init() {
            // Make instance globally accessible for reset
            const instanceName = this.fieldName + 'DrawerInstance';
            window[instanceName] = this;

            // Sync initial state with parent form
            if (window.contactForm) {
                window.contactForm.formData[this.fieldName] = this.selected;
            }

            // Watch for changes and sync to parent form (without validation to avoid conflicts)
            this.$watch('selected', () => {
                if (window.contactForm) {
                    window.contactForm.formData[this.fieldName] = this.selected;
                }
            });
        },

        // COMPUTED
        buttonText() {
            return this.selected || "";
        },

        isVisible(value) {
            if (!this.searchTerm) return true;
            return value.toString().toLowerCase().includes(this.searchTerm.toLowerCase());
        }
    })
    Alpine.data('monthSelector', () => handleSelect('month'));
    Alpine.data('monthDrawer', () => handleSelect('month'));
    Alpine.data('yearDrawer', () => handleSelect('year'));
    Alpine.data('numberDrawer', () => handleSelect('number'));
    Alpine.data('phoneDrawer', () => ({
        // STATE
        restcountries: [],
        isOpen: false,
        selected: '+84', // Default to Vietnam
        isLoading: false,
        isDataLoaded: false,
        searchTerm: '',

        async init() {
            // Make instance globally accessible for reset
            window.phoneDrawerInstance = this;

            // Sync with parent form data
            if (window.contactForm) {
                window.contactForm.formData.phone_national = this.selected;
            }

            // Watch for changes and sync to parent form
            this.$watch('selected', () => {
                if (window.contactForm) {
                    window.contactForm.formData.phone_national = this.selected;
                }
            });

            console.log('phoneDrawer init called');
            // Load default country data immediately for better UX
            await this.loadCountries();
        },

        async loadCountries() {
            if (this.isDataLoaded) return; // Already loaded

            this.isLoading = true;
            try {
                const response = await fetch("https://restcountries.com/v3.1/all?fields=idd,name,flags");
                const data = await response.json();
                console.log('API Response - Total countries:', data.length);

                // Process countries with IDD - combine root and suffixes
                const processedCountries = data
                    .filter(country => country.idd && country.idd.root)
                    .map(country => ({
                        ...country,
                        idd: {
                            ...country.idd,
                            fullCode: country.idd.suffixes && country.idd.suffixes.length > 0
                                ? country.idd.root + country.idd.suffixes[0]
                                : country.idd.root
                        }
                    }));

                console.log('Countries with IDD:', processedCountries.length);
                console.log('Sample full codes:', processedCountries.slice(0, 5).map(c => ({ name: c.name.common, fullCode: c.idd.fullCode })));

                // Remove duplicates by country name
                const nameDuplicates = processedCountries.length - processedCountries.filter((country, index, self) =>
                    index === self.findIndex(c => c.name.common === country.name.common)
                ).length;
                console.log('Name duplicates removed:', nameDuplicates);

                const uniqueCountries = processedCountries
                    .filter((country, index, self) =>
                        index === self.findIndex(c => c.name.common === country.name.common)
                    )
                    .sort((a, b) => a.name.common.localeCompare(b.name.common));

                console.log('Final filtered countries:', uniqueCountries.length);

                this.restcountries = uniqueCountries;
                this.isDataLoaded = true;
                this.isLoading = false;
            } catch (error) {
                console.error('Error fetching countries:', error);
                // Fallback data
                this.restcountries = [
                    { idd: { root: '+1', fullCode: '+1' }, name: { common: 'United States' }, flags: { png: '' } },
                    { idd: { root: '+84', fullCode: '+84' }, name: { common: 'Vietnam' }, flags: { png: '' } },
                    { idd: { root: '+44', fullCode: '+44' }, name: { common: 'United Kingdom' }, flags: { png: '' } },
                    { idd: { root: '+86', fullCode: '+86' }, name: { common: 'China' }, flags: { png: '' } },
                    { idd: { root: '+81', fullCode: '+81' }, name: { common: 'Japan' }, flags: { png: '' } }
                ];
                this.isDataLoaded = true;
                this.isLoading = false;
            }
        },

        // METHODS
        toggleDropdown() {
            if (!this.isOpen) {
                // Disable scroll directly
                document.documentElement.style.overflow = 'hidden';
                console.log('Scroll disabled by phoneDrawer');
            }
            this.isOpen = !this.isOpen;
        },

        closeDropdown() {
            if (this.isOpen) {
                this.isOpen = false;
                // Enable scroll directly
                document.documentElement.style.overflow = '';
                console.log('Scroll enabled by phoneDrawer');
            }
            this.clearSearch(); // Clear search when closing
        },

        select(country) {
            this.selected = country.idd.fullCode;
            this.closeDropdown();
        },

        clearSelection() {
            this.selected = '';
        },

        clearSearch() {
            this.searchTerm = '';
        },

        // METHODS
        getSelectedCountry() {
            // Handle default selection before data is loaded
            if (!this.isDataLoaded && this.selected) {
                return {
                    idd: { fullCode: this.selected },
                    name: { common: this.getCountryNameFromCode(this.selected) },
                    flags: { png: this.getFlagFromCode(this.selected) }
                };
            }
            return this.restcountries.find(country => country.idd.fullCode === this.selected);
        },

        getCountryNameFromCode(code) {
            const countryMap = {
                '+84': 'Vietnam',
                '+1': 'United States',
                '+44': 'United Kingdom',
                '+86': 'China',
                '+81': 'Japan'
            };
            return countryMap[code] || 'Unknown';
        },

        getFlagFromCode(code) {
            const flagMap = {
                '+84': 'https://flagcdn.com/vn.svg',
                '+1': 'https://flagcdn.com/us.svg',
                '+44': 'https://flagcdn.com/gb.svg',
                '+86': 'https://flagcdn.com/cn.svg',
                '+81': 'https://flagcdn.com/jp.svg'
            };
            return flagMap[code] || '';
        },

        getSelectedCountryFlag() {
            const country = this.getSelectedCountry();
            return country ? country.flags.png : '';
        },

        getSelectedCountryName() {
            const country = this.getSelectedCountry();
            return country ? country.name.common : '';
        },

        getSelectedDisplay() {
            return this.selected || '';
        },

        // COMPUTED
        buttonText() {
            return this.selected || "";
        },

        filteredCountries() {
            if (!this.searchTerm) return this.restcountries;
            return this.restcountries.filter(country =>
                country.name.common.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                country.idd.fullCode.toLowerCase().includes(this.searchTerm.toLowerCase())
            );
        }
    }));
    Alpine.data('yearSelector', () => handleSelect('year'));
    Alpine.data('numberSelector', () => handleSelect('number'));

    Alpine.data('budgetSlider', () => ({
        min: 3200,
        max: 14235,
        isInitialized: false,

        init() {
            // Make instance globally accessible for reset
            window.budgetSliderInstance = this;

            // Sync with parent form data
            if (window.contactForm) {
                window.contactForm.formData.budget_min = this.min;
                window.contactForm.formData.budget_max = this.max;
            }

            // Watch for changes and sync to parent form
            this.$watch('min', () => {
                if (window.contactForm) {
                    if (window.contactForm.formData?.budget_later) return;
                    window.contactForm.formData.budget_min = this.min;
                }
            });

            this.$watch('max', () => {
                if (window.contactForm) {
                    if (window.contactForm.formData?.budget_later) return;
                    window.contactForm.formData.budget_max = this.max;
                }
            });

            // Prevent double initialization
            if (this.isInitialized) {
                return;
            }

            // Check if noUiSlider is available
            if (typeof noUiSlider === 'undefined') {
                console.error('noUiSlider library not loaded for budget slider');
                return;
            }

            // Destroy existing slider if any
            if (this.$refs.slider && this.$refs.slider.noUiSlider) {
                this.$refs.slider.noUiSlider.destroy();
            }

            try {
                // Define custom format for tooltips
                const dollarFormat = {
                    from: function (formattedValue) {
                        return Number(formattedValue.replace(/[$,]/g, ''));
                    },
                    to: function (numericValue) {
                        return '$' + Math.round(numericValue).toLocaleString();
                    }
                };

                noUiSlider.create(this.$refs.slider, {
                    start: [this.min, this.max],
                    connect: true,
                    tooltips: [dollarFormat, dollarFormat], // Apply format to both handles
                    range: {
                        min: 2000,
                        max: 20000
                    },
                    step: 5
                });

                this.$refs.slider.noUiSlider.on('update', values => {
                    if (window.contactForm?.formData?.budget_later) return;
                    const v0 = dollarFormat.from(values[0]);
                    const v1 = dollarFormat.from(values[1]);
                    const min = Math.min(v0, v1);
                    const max = Math.max(v0, v1);
                    this.min = Math.round(min);
                    this.max = Math.round(max);
                });

                // Disable/enable slider when user selects "decide budget later"
                this.$watch(() => window.contactForm?.formData?.budget_later, (val) => {
                    if (!this.$refs.slider?.noUiSlider) return;
                    if (val) {
                        this.$refs.slider.noUiSlider.disable();
                        if (window.contactForm?.formData) {
                            window.contactForm.formData.budget_min = '';
                            window.contactForm.formData.budget_max = '';
                        }
                    } else {
                        this.$refs.slider.noUiSlider.enable();
                        if (window.contactForm?.formData) {
                            window.contactForm.formData.budget_min = this.min;
                            window.contactForm.formData.budget_max = this.max;
                        }
                    }
                });

                if (window.contactForm?.formData?.budget_later) {
                    this.$refs.slider.noUiSlider.disable();
                }

                this.isInitialized = true;
            } catch (error) {
                console.error('Error initializing budget slider:', error);
            }
        },

        reset() {
            // Reset slider values
            this.min = 3200;
            this.max = 14235;
            if (this.$refs.slider && this.$refs.slider.noUiSlider) {
                this.$refs.slider.noUiSlider.set([3200, 14235]);
            }
        },

        destroy() {
            // Cleanup on component destroy
            if (this.$refs.slider && this.$refs.slider.noUiSlider) {
                this.$refs.slider.noUiSlider.destroy();
            }
            this.isInitialized = false;
        },

        format(v) {
            return '$' + v.toLocaleString();
        }
    }));

    // Form Validation Component
    Alpine.data('contactForm', () => ({
        // PRIVATE

        // STATE
        formData: {
            destinations: [],
            month: '',
            year: '',
            duration: '',
            number: '',
            budget_min: '',
            budget_max: '',
            first_name: '',
            last_name: '',
            email: '',
            confirm_email: '',
            phone: '',
            phone_national: '+84',
            comments: '',
            budget_later: false,
            accept_video: false
        },
        errors: {},
        isSubmitting: false,
        submitMessage: '',
        hasBeenInteracted: false, // Track if user has interacted with form

        // VALIDATION RULES
        validationRules: {
            destinations: {
                presence: {
                    allowEmpty: false,
                    message: "Select at least one destination"
                },
                length: {
                    minimum: 1,
                    message: "Select at least one destination"
                }
            },
            month: {
                presence: {
                    allowEmpty: false,
                    message: "Select a travel month"
                }
            },
            year: {
                presence: {
                    allowEmpty: false,
                    message: "Select a travel year"
                }
            },
            number: {
                presence: {
                    allowEmpty: false,
                    message: "Select number of travelers"
                }
            },
            first_name: {
                presence: {
                    allowEmpty: false,
                    message: "Enter your first name"
                },
            },
            email: {
                presence: {
                    allowEmpty: false,
                    message: "Enter your email address"
                },
                email: {
                    message: "Enter a valid email address"
                }
            },
            confirm_email: {
                presence: {
                    allowEmpty: false,
                    message: "Confirm your email address"
                },
                equality: {
                    attribute: "email",
                    message: "Email addresses don't match"
                }
            },
            phone: {
                presence: {
                    allowEmpty: false,
                    message: "Enter your phone number"
                },
                format: {
                    pattern: /^[\d\s\-\+\(\)]{8,20}$/,
                    message: "Enter a valid phone number"
                }
            },
            comments: {
                presence: {
                    allowEmpty: false,
                    message: "Please provide your travel requirements"
                },
            },
            duration: {
                presence: {
                    allowEmpty: false,
                    message: "Please specify trip duration"
                }
            }
        },

        // METHODS

        validateInput(fieldName) {
            // Mark that user has interacted with the form
            this.hasBeenInteracted = true;
            this.validateField(fieldName);
        },

        validateField(fieldName) {
            if (typeof validate === 'undefined') {
                console.warn('validate function not available');
                return;
            }

            const constraints = {};
            constraints[fieldName] = this.validationRules[fieldName];

            const result = validate(this.formData, constraints, {
                fullMessages: false
            });

            console.log(`Validation result for ${fieldName}:`, result);

            if (result && result[fieldName]) {
                // Only show the first error message to avoid displaying multiple messages simultaneously
                let errorMessage = result[fieldName];
                console.log(`Raw errorMessage for ${fieldName}:`, errorMessage);

                if (Array.isArray(errorMessage)) {
                    errorMessage = errorMessage[0]; // Take only the first error
                    console.log(`Taking first error from array:`, errorMessage);
                } else if (typeof errorMessage === 'object') {
                    // If it's an object, get the first value
                    const keys = Object.keys(errorMessage);
                    if (keys.length > 0) {
                        errorMessage = errorMessage[keys[0]];
                        console.log(`Taking first error from object:`, errorMessage);
                    }
                }

                this.errors[fieldName] = errorMessage;
            } else {
                // Clear error for this field only, keep others
                delete this.errors[fieldName];
            }
        },

        validateForm() {
            if (typeof validate === 'undefined') {
                console.warn('validate function not available');
                return true; // Allow submission if validation not available
            }

            // Unwrap Alpine Proxy to plain object for validation
            const plainFormData = JSON.parse(JSON.stringify(this.formData));
            const result = validate(plainFormData, this.validationRules, {
                fullMessages: false
            });

            console.log('Full form validation result:', result);

            // Process errors to show only one message per field
            if (result) {
                const processedErrors = {};
                for (const fieldName in result) {
                    let errorMessage = result[fieldName];
                    console.log(`Processing ${fieldName} error:`, errorMessage);

                    if (Array.isArray(errorMessage)) {
                        errorMessage = errorMessage[0]; // Take only the first error
                        console.log(`Taking first error from array:`, errorMessage);
                    } else if (typeof errorMessage === 'object') {
                        // If it's an object, get the first value
                        const keys = Object.keys(errorMessage);
                        if (keys.length > 0) {
                            errorMessage = errorMessage[keys[0]];
                            console.log(`Taking first error from object:`, errorMessage);
                        }
                    }

                    processedErrors[fieldName] = errorMessage;
                }
                this.errors = processedErrors;
            } else {
                this.errors = {};
            }

            return !result; // Return true if no errors
        },

        async submitForm() {
            // Reset previous state
            this.submitMessage = '';
            this.isSubmitting = true;

            try {
                // Wait a bit for scripts to load if needed
                if (typeof validate === 'undefined') {
                    await new Promise(resolve => setTimeout(resolve, 100));
                }

                // Validate form
                if (!this.validateForm()) {
                    if (window.globalToast) {
                        window.globalToast.showToast('Please correct the errors below.');
                    }
                    this.isSubmitting = false;
                    return;
                }
                // Submit to Contact Form 7 API
                const formData = new FormData();
                const tour = document.querySelector('.section-form__tour-title')
                if (tour) {
                    formData.append('tour-name', tour.innerHTML);
                }
                // Map form data to CF7 field names
                formData.append('_wpcf7_unit_tag', '66f9759');
                formData.append('first-name', this.formData.first_name);
                formData.append('last-name', this.formData.last_name);
                formData.append('your-email', this.formData.email);
                formData.append('your-phone', '(' + this.formData.phone_national + ') ' + this.formData.phone);
                formData.append('destinations', this.formData.destinations.join(', '));
                formData.append('travel-month', this.formData.month);
                formData.append('travel-year', this.formData.year);
                formData.append('duration', this.formData.duration);
                formData.append('number-of-people', this.formData.number);
                if (!this.formData.budget_later) {
                    formData.append('budget-min', this.formData.budget_min);
                    formData.append('budget-max', this.formData.budget_max);
                }
                formData.append('comments', this.formData.comments);
                formData.append('budget-later', this.formData.budget_later ? 'yes' : 'no');
                formData.append('accept-video', this.formData.accept_video ? 'yes' : 'no');

                // Log form data for debugging
                console.log('Submitting to CF7 API with data:', Object.fromEntries(formData));

                try {
                    const response = await fetch('/wp-json/contact-form-7/v1/contact-forms/1754/feedback', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();
                    console.log('CF7 API Response:', result);

                    if (result.status === 'mail_sent') {
                        // Success - Build thank you URL with form data parameters
                        // Helper function to encode URL parameter value with cleaner formatting
                        const encodeParam = (value, allowSpecialChars = false, allowUnicode = false) => {
                            if (!value) return '';
                            const str = String(value).trim();
                            
                            if (allowSpecialChars) {
                                // For values that can contain special chars (like email, phone)
                                // Only encode truly dangerous characters, keep @, +, ., -, _, etc.
                                // Characters that are safe in URL query values: A-Z, a-z, 0-9, -_.~!*'();:@&=+$,/?#[]
                                // We'll only encode: &, =, #, ?, %, space
                                return str
                                    .replace(/%/g, '%25')
                                    .replace(/&/g, '%26')
                                    .replace(/#/g, '%23')
                                    .replace(/\?/g, '%3F')
                                    .replace(/=/g, '%3D')
                                    .replace(/ /g, '+');
                            } else if (allowUnicode) {
                                // For Unicode text (like names), use standard encoding
                                // Modern browsers will display Unicode nicely in the address bar
                                // even though it's encoded in the actual URL
                                return encodeURIComponent(str).replace(/%20/g, '+');
                            } else {
                                // Standard encoding but use + for spaces (more readable)
                                return encodeURIComponent(str).replace(/%20/g, '+');
                            }
                        };

                        // Helper function to clean tour name
                        const cleanTourName = (html) => {
                            if (!html) return '';
                            // Remove HTML tags and entities, trim whitespace
                            const temp = document.createElement('div');
                            temp.innerHTML = html;
                            return temp.textContent || temp.innerText || '';
                        };

                        // Helper function to convert month name to number (2 digits)
                        const monthToNumber = (monthName) => {
                            if (!monthName) return '';
                            const months = {
                                'January': '01', 'February': '02', 'March': '03', 'April': '04',
                                'May': '05', 'June': '06', 'July': '07', 'August': '08',
                                'September': '09', 'October': '10', 'November': '11', 'December': '12'
                            };
                            return months[monthName] || monthName;
                        };

                        // Build query parameters array
                        const queryParams = [];

                        // Add destinations (join with comma, no space)
                        if (this.formData.destinations && this.formData.destinations.length > 0) {
                            queryParams.push(`destinations=${encodeParam(this.formData.destinations.join(','))}`);
                        }

                        // Add month (convert to number)
                        if (this.formData.month) {
                            const monthNumber = monthToNumber(this.formData.month);
                            queryParams.push(`month_num=${encodeParam(monthNumber)}`);
                        }

                        // Add year
                        if (this.formData.year) {
                            queryParams.push(`year_num=${encodeParam(this.formData.year)}`);
                        }

                        // Add duration
                        if (this.formData.duration) {
                            queryParams.push(`duration=${encodeParam(this.formData.duration)}`);
                        }

                        // Add number of people
                        if (this.formData.number) {
                            queryParams.push(`number=${encodeParam(this.formData.number)}`);
                        }

                        // Add budget range
                        if (!this.formData.budget_later) {
                            if (this.formData.budget_min) {
                                queryParams.push(`budget_min=${encodeParam(this.formData.budget_min)}`);
                            }
                            if (this.formData.budget_max) {
                                queryParams.push(`budget_max=${encodeParam(this.formData.budget_max)}`);
                            }
                        }

                        // Add personal info
                        if (this.formData.first_name) {
                            queryParams.push(`first_name=${encodeParam(this.formData.first_name, false, true)}`);
                        }
                        if (this.formData.last_name) {
                            queryParams.push(`last_name=${encodeParam(this.formData.last_name, false, true)}`);
                        }
                        if (this.formData.email) {
                            queryParams.push(`email=${encodeParam(this.formData.email, true)}`);
                        }
                        if (this.formData.confirm_email) {
                            queryParams.push(`confirm_email=${encodeParam(this.formData.confirm_email, true)}`);
                        }
                        if (this.formData.phone) {
                            queryParams.push(`phone=${encodeParam(this.formData.phone)}`);
                        }
                        if (this.formData.phone_national) {
                            queryParams.push(`phone_national=${encodeParam(this.formData.phone_national, true)}`);
                        }

                        // Add comments (only if not empty)
                        if (this.formData.comments && this.formData.comments.trim()) {
                            queryParams.push(`comments=${encodeParam(this.formData.comments)}`);
                        }

                        // Add optional flags
                        if (this.formData.budget_later) {
                            queryParams.push(`budget_later=yes`);
                        }
                        if (this.formData.accept_video) {
                            queryParams.push(`accept_video=yes`);
                        }

                        // Add tour name if available (clean HTML)
                        const tour = document.querySelector('.section-form__tour-title');
                        if (tour) {
                            const tourName = cleanTourName(tour.innerHTML);
                            if (tourName.trim()) {
                                queryParams.push(`tour_name=${encodeParam(tourName)}`);
                            }
                        }

                        // Build final URL
                        const thankYouUrl = '/thankyou' + (queryParams.length > 0 ? '?' + queryParams.join('&') : '');
                        console.log('Redirecting to:', thankYouUrl);
                        window.location.href = thankYouUrl;
                    } else {
                        // CF7 validation errors
                        if (result.invalid_fields && result.invalid_fields.length > 0) {
                            const errorMessages = result.invalid_fields.map(field => field.message).join(', ');
                            if (window.globalToast) {
                                window.globalToast.showToast('Please check the following: ' + errorMessages, 'error');
                            }
                        } else {
                            // Generic error
                            if (window.globalToast) {
                                window.globalToast.showToast(result.message || 'There was an error submitting your enquiry. Please try again.', 'error');
                            }
                        }
                    }
                } catch (apiError) {
                    console.error('CF7 API Error:', apiError);
                    if (window.globalToast) {
                        window.globalToast.showToast('Network error. Please check your connection and try again.', 'error');
                    }
                }

                this.isSubmitting = false;

            } catch (error) {
                console.error('Form submission error:', error);
                if (window.globalToast) {
                    window.globalToast.showToast('There was an error submitting your enquiry. Please try again.');
                }
                this.isSubmitting = false;
            }
        },
        resetForm() {
            // Reset form data
            this.formData = {
                destinations: [],
                month: '',
                year: '',
                duration: '',
                number: '',
                budget_min: 3200,
                budget_max: 14235,
                first_name: '',
                last_name: '',
                email: '',
                confirm_email: '',
                phone: '',
                phone_national: '+84',
                comments: '',
                budget_later: false,
                accept_video: false
            };

            // Clear errors
            this.errors = {};

            // Reset all Alpine components
            this.resetAlpineComponents();
        },

        resetAlpineComponents() {
            // Reset destination drawer
            if (window.destinationDrawerInstance) {
                window.destinationDrawerInstance.selectedDestinations = [];
                window.destinationDrawerInstance.searchTerm = '';
                window.destinationDrawerInstance.isOpen = false;
                // Close drawer overlay
                const destOverlay = document.querySelector('.section-form__destination .section-form__drawer-overlay');
                if (destOverlay) {
                    destOverlay.classList.remove('section-form__drawer-overlay--open');
                }
            }

            // Reset destination selector UI
            const destSelectors = document.querySelectorAll('.section-form__select-destination');
            destSelectors.forEach(destSelector => {
                const noSelectElement = destSelector.querySelector('.section-form__no-select-destination');
                const selectedElements = destSelector.querySelectorAll('.section-form__selected-destination');

                // Show "Select destinations" text
                if (noSelectElement) {
                    noSelectElement.style.display = 'block';
                }

                // Hide all selected destination tags
                selectedElements.forEach(el => {
                    el.style.display = 'none';
                });
            });

            // Uncheck all destination checkboxes
            const destinationCheckboxes = document.querySelectorAll('input[name="destination[]"]');
            destinationCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                // Also reset Alpine x-model if it exists
                if (checkbox._x_model) {
                    checkbox._x_model.set([]);
                }
            });

            // Force update destination drawer instance if it exists
            setTimeout(() => {
                if (window.destinationDrawerInstance) {
                    // Trigger Alpine reactivity update
                    window.destinationDrawerInstance.selectedDestinations = [...window.destinationDrawerInstance.selectedDestinations];
                }
            }, 0);

            // Reset drawer selector UIs (month, year, number, phone)
            const drawerSelectors = document.querySelectorAll('.section-form__select');
            drawerSelectors.forEach(selector => {
                const noSelectedElement = selector.querySelector('.section-form__no-selected');
                const selectedTextElement = selector.querySelector('.section-form__selected-text');

                // Show placeholder text
                if (noSelectedElement) {
                    noSelectedElement.style.display = 'block';
                }

                // Hide selected text
                if (selectedTextElement) {
                    selectedTextElement.style.display = 'none';
                }
            });

            // Reset month drawer
            if (window.monthDrawerInstance) {
                window.monthDrawerInstance.selected = '';
                window.monthDrawerInstance.searchTerm = '';
                window.monthDrawerInstance.isOpen = false;
                // Close drawer overlay
                const monthOverlay = document.querySelector('.section-form__select-option.x-data\\=monthDrawer .section-form__drawer-overlay');
                if (monthOverlay) {
                    monthOverlay.classList.remove('section-form__drawer-overlay--open');
                }
            }

            // Reset year drawer
            if (window.yearDrawerInstance) {
                window.yearDrawerInstance.selected = '';
                window.yearDrawerInstance.searchTerm = '';
                window.yearDrawerInstance.isOpen = false;
                // Close drawer overlay
                const yearOverlay = document.querySelector('.section-form__select-option.x-data\\=yearDrawer .section-form__drawer-overlay');
                if (yearOverlay) {
                    yearOverlay.classList.remove('section-form__drawer-overlay--open');
                }
            }

            // Reset number drawer
            if (window.numberDrawerInstance) {
                window.numberDrawerInstance.selected = '';
                window.numberDrawerInstance.searchTerm = '';
                window.numberDrawerInstance.isOpen = false;
                // Close drawer overlay
                const numberOverlay = document.querySelector('.section-form__select-option.x-data\\=numberDrawer .section-form__drawer-overlay');
                if (numberOverlay) {
                    numberOverlay.classList.remove('section-form__drawer-overlay--open');
                }
            }

            // Reset phone drawer
            if (window.phoneDrawerInstance) {
                window.phoneDrawerInstance.selected = '+84';
                window.phoneDrawerInstance.searchTerm = '';
                window.phoneDrawerInstance.isOpen = false;
                // Close drawer overlay
                const phoneOverlay = document.querySelector('.section-form__select-option.x-data\\=phoneDrawer .section-form__drawer-overlay');
                if (phoneOverlay) {
                    phoneOverlay.classList.remove('section-form__drawer-overlay--open');
                }
            }

            // Reset phone national selector
            if (window.phoneNationalSelectorInstance) {
                window.phoneNationalSelectorInstance.selected = '+84';
                window.phoneNationalSelectorInstance.searchTerm = '';
                window.phoneNationalSelectorInstance.isOpen = false;
            }

            // Reset phone selector UI (flag and text)
            const phoneSelectors = document.querySelectorAll('.section-form__select-option input[name="phone-national"]');
            phoneSelectors.forEach(selector => {
                // Reset the hidden input value
                selector.value = '+84';

                // Find the parent select element and reset its display
                const selectElement = selector.closest('.section-form__select-option');
                if (selectElement) {
                    const flagImg = selectElement.querySelector('.section-form__select-flag');
                    const selectedText = selectElement.querySelector('.section-form__selected-text:not(.section-form__selected-text--flag)');

                    // Reset flag to Vietnam
                    if (flagImg) {
                        flagImg.src = 'https://flagcdn.com/vn.svg';
                        flagImg.style.display = 'block';
                    }

                    // Reset text to +84
                    if (selectedText) {
                        selectedText.textContent = '+84';
                        selectedText.style.display = 'block';
                    }

                    // Hide any selected country name
                    const selectedCountryElements = selectElement.querySelectorAll('.section-form__selected-text--flag');
                    selectedCountryElements.forEach(el => {
                        el.style.display = 'none';
                    });
                }
            });

            // Reset checkboxes
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
                // Also update Alpine data if bound
                const name = checkbox.name;
                if (name && this.formData.hasOwnProperty(name.replace('-', '_'))) {
                    this.formData[name.replace('-', '_')] = false;
                }
            });

            // Reset text inputs and textareas
            const textInputs = document.querySelectorAll('input[type="text"], input[type="email"], textarea');
            textInputs.forEach(input => {
                if (input.name && input.name !== 'phone-national') {
                    input.value = '';
                    // Also update Alpine data if bound
                    const fieldName = input.name.replace('-', '_');
                    if (this.formData.hasOwnProperty(fieldName)) {
                        this.formData[fieldName] = '';
                    }
                }
            });

            // Reset select dropdowns (for desktop version)
            const selectElements = document.querySelectorAll('select');
            selectElements.forEach(select => {
                select.selectedIndex = 0;
            });

            // Force close any open drawers/modals
            const drawerOverlays = document.querySelectorAll('.section-form__drawer-overlay');
            drawerOverlays.forEach(overlay => {
                overlay.classList.remove('section-form__drawer-overlay--open');
            });

            // Reset budget slider if it exists
            if (window.budgetSliderInstance) {
                if (typeof window.budgetSliderInstance.reset === 'function') {
                    window.budgetSliderInstance.reset();
                } else {
                    // Manual reset if reset method not available
                    window.budgetSliderInstance.min = 120;
                    window.budgetSliderInstance.max = 4235;
                    if (window.budgetSliderInstance.$refs.slider && window.budgetSliderInstance.$refs.slider.noUiSlider) {
                        window.budgetSliderInstance.$refs.slider.noUiSlider.set([120, 4235]);
                    }
                }
            }
        },

        // Watch for form data changes to sync with form elements
        init() {
            // Make form accessible globally for child components
            window.contactForm = this;

            // Sync with existing form elements without triggering validation
            this.syncWithFormElements();

            // Removed watcher that was causing full form validation on every data change
            // Realtime validation is now handled by individual field validation in validateInput()
            // and select components handle their own validation
        },

        syncWithFormElements() {
            // Sync destinations
            const destCheckboxes = document.querySelectorAll('input[name="destination[]"]:checked');
            this.formData.destinations = Array.from(destCheckboxes).map(cb => cb.value);

            // Sync duration
            const durationInput = document.querySelector('input[name="duration"]');
            if (durationInput) this.formData.duration = durationInput.value;

            // Sync other form fields
            const form = document.querySelector('.section-form__left');
            if (form) {
                const inputs = form.querySelectorAll('input:not([type="checkbox"]):not([type="radio"]), select, textarea');
                inputs.forEach(input => {
                    if (input.name && this.formData.hasOwnProperty(input.name)) {
                        this.formData[input.name] = input.value;
                    }
                });
            }
        }
    }));

    Alpine.data('phoneNationalSelector', () => ({
        // STATE
        restcountries: [],
        isOpen: false,
        selected: '+84', // Default to Vietnam
        isLoading: false,
        isDataLoaded: false,
        searchTerm: '',
        async init() {
            // Make instance globally accessible for reset
            window.phoneNationalSelectorInstance = this;

            // Sync with parent form data
            if (window.contactForm) {
                window.contactForm.formData.phone_national = this.selected;
            }

            // Watch for changes and sync to parent form
            this.$watch('selected', () => {
                if (window.contactForm) {
                    window.contactForm.formData.phone_national = this.selected;
                }
            });

            console.log('phoneNationalSelector init called');
            // Load default country data immediately for better UX
            await this.loadCountries();
        },

        async loadCountries() {
            if (this.isDataLoaded) return; // Already loaded

            this.isLoading = true;
            try {
                const response = await fetch("https://restcountries.com/v3.1/all?fields=idd,name,flags");
                const data = await response.json();
                console.log('API Response - Total countries:', data.length);

                // Process countries with IDD - combine root and suffixes
                const processedCountries = data
                    .filter(country => country.idd && country.idd.root)
                    .map(country => ({
                        ...country,
                        idd: {
                            ...country.idd,
                            fullCode: country.idd.suffixes && country.idd.suffixes.length > 0
                                ? country.idd.root + country.idd.suffixes[0]
                                : country.idd.root
                        }
                    }));

                console.log('Countries with IDD:', processedCountries.length);
                console.log('Sample full codes:', processedCountries.slice(0, 5).map(c => ({ name: c.name.common, fullCode: c.idd.fullCode })));

                // Remove duplicates by country name
                const nameDuplicates = processedCountries.length - processedCountries.filter((country, index, self) =>
                    index === self.findIndex(c => c.name.common === country.name.common)
                ).length;
                console.log('Name duplicates removed:', nameDuplicates);

                const uniqueCountries = processedCountries
                    .filter((country, index, self) =>
                        index === self.findIndex(c => c.name.common === country.name.common)
                    )
                    .sort((a, b) => a.name.common.localeCompare(b.name.common));

                console.log('Final filtered countries:', uniqueCountries.length);

                this.restcountries = uniqueCountries;
                this.isDataLoaded = true;
                this.isLoading = false;
            } catch (error) {
                console.error('Error fetching countries:', error);
                // Fallback data
                this.restcountries = [
                    { idd: { root: '+1', fullCode: '+1' }, name: { common: 'United States' }, flags: { png: '' } },
                    { idd: { root: '+84', fullCode: '+84' }, name: { common: 'Vietnam' }, flags: { png: '' } },
                    { idd: { root: '+44', fullCode: '+44' }, name: { common: 'United Kingdom' }, flags: { png: '' } },
                    { idd: { root: '+86', fullCode: '+86' }, name: { common: 'China' }, flags: { png: '' } },
                    { idd: { root: '+81', fullCode: '+81' }, name: { common: 'Japan' }, flags: { png: '' } }
                ];
                this.isDataLoaded = true;
                this.isLoading = false;
            }
        },
        // METHODS
        toggleDropdown() {
            this.isOpen = !this.isOpen;
        },

        closeDropdown() {
            this.isOpen = false;
            this.clearSearch(); // Clear search when closing
        },

        select(country) {
            this.selected = country.idd.fullCode;
            this.closeDropdown();
        },

        clearSelection() {
            this.selected = '';
        },

        clearSearch() {
            this.searchTerm = '';
        },

        // METHODS
        getSelectedCountry() {
            // Handle default selection before data is loaded
            if (!this.isDataLoaded && this.selected) {
                return {
                    idd: { fullCode: this.selected },
                    name: { common: this.getCountryNameFromCode(this.selected) },
                    flags: { png: this.getFlagFromCode(this.selected) }
                };
            }
            return this.restcountries.find(country => country.idd.fullCode === this.selected);
        },

        getCountryNameFromCode(code) {
            const countryMap = {
                '+84': 'Vietnam',
                '+1': 'United States',
                '+44': 'United Kingdom',
                '+86': 'China',
                '+81': 'Japan'
            };
            return countryMap[code] || 'Unknown';
        },

        getFlagFromCode(code) {
            const flagMap = {
                '+84': 'https://flagcdn.com/vn.svg',
                '+1': 'https://flagcdn.com/us.svg',
                '+44': 'https://flagcdn.com/gb.svg',
                '+86': 'https://flagcdn.com/cn.svg',
                '+81': 'https://flagcdn.com/jp.svg'
            };
            return flagMap[code] || '';
        },

        getSelectedCountryFlag() {
            const country = this.getSelectedCountry();
            return country ? country.flags.png : '';
        },

        getSelectedCountryName() {
            const country = this.getSelectedCountry();
            return country ? country.name.common : '';
        },

        getSelectedDisplay() {
            return this.selected || '';
        },

        // COMPUTED
        buttonText() {
            return this.selected || "";
        },

        filteredCountries() {
            if (!this.searchTerm) return this.restcountries;
            return this.restcountries.filter(country =>
                country.name.common.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                country.idd.fullCode.toLowerCase().includes(this.searchTerm.toLowerCase())
            );
        }
    }));
});




