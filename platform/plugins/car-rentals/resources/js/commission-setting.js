(() => {
    'use strict';

    const initCategoryCommissionSetting = () => {
        const categoryCommissionSettingWrapper = document.querySelector('.commission-setting-item-wrapper');
        if (!categoryCommissionSettingWrapper) {
            return;
        }

        const categoryCommissionSettingItems = categoryCommissionSettingWrapper.querySelectorAll('.commission-setting-item');
        const categoryCommissionSettingItemTemplate = categoryCommissionSettingItems[0].outerHTML;

        const addNewCategoryCommissionSettingButton = document.querySelector('[data-bb-toggle="commission-category-add"]');
        if (addNewCategoryCommissionSettingButton) {
            addNewCategoryCommissionSettingButton.addEventListener('click', (event) => {
                event.preventDefault();
                const newIndex = categoryCommissionSettingWrapper.querySelectorAll('.commission-setting-item').length;
                const template = categoryCommissionSettingItemTemplate.replace(/\[0\]/g, `[${newIndex}]`);
                const newItem = document.createElement('div');
                newItem.innerHTML = template;
                const newCommissionItem = newItem.querySelector('.commission-setting-item');
                newCommissionItem.id = `commission-setting-item-${newIndex}`;

                // Clean up any Tagify-related elements and attributes from the template
                const textarea = newCommissionItem.querySelector('textarea.tagify-commission-setting');
                if (textarea) {
                    // Clear the value and remove any Tagify-related attributes
                    textarea.value = '';
                    textarea.removeAttribute('readonly');
                    textarea.removeAttribute('tabindex');

                    // Remove any Tagify elements that might have been cloned
                    const parent = textarea.parentNode;
                    const tagifyElements = parent.querySelectorAll('.tagify');
                    tagifyElements.forEach(el => el.remove());

                    // Remove any hidden inputs that Tagify might have added
                    const hiddenInputs = parent.querySelectorAll('input[type="hidden"]');
                    hiddenInputs.forEach(el => el.remove());
                }

                const rowDiv = newCommissionItem.querySelector('.row .col-9 .row');

                // Check if there's already a col-2 div
                let deleteButtonWrapper = rowDiv.querySelector('.col-2');

                // If no col-2 div exists, create one
                if (!deleteButtonWrapper) {
                    deleteButtonWrapper = document.createElement('div');
                    deleteButtonWrapper.classList.add('col-2');
                    rowDiv.appendChild(deleteButtonWrapper);
                }

                // Clear any existing content in the wrapper
                deleteButtonWrapper.innerHTML = '';

                // Create and add the delete button
                const deleteButton = document.createElement('button');
                deleteButton.classList.add('btn', 'btn-icon');
                deleteButton.setAttribute('data-bb-toggle', 'commission-remove');
                deleteButton.setAttribute('data-index', newIndex);
                deleteButton.innerHTML = '<svg\n' +
                    ' class="icon icon-left svg-icon-ti-ti-trash"\n' +
                    '  xmlns="http://www.w3.org/2000/svg"\n' +
                    '  width="24"\n' +
                    '  height="24"\n' +
                    '  viewBox="0 0 24 24"\n' +
                    '  fill="none"\n' +
                    '  stroke="currentColor"\n' +
                    '  stroke-width="2"\n' +
                    '  stroke-linecap="round"\n' +
                    '  stroke-linejoin="round"\n' +
                    '  >\n' +
                    '  <path stroke="none" d="M0 0h24v24H0z" fill="none"/>\n' +
                    '  <path d="M4 7l16 0" />\n' +
                    '  <path d="M10 11l0 6" />\n' +
                    '  <path d="M14 11l0 6" />\n' +
                    '  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />\n' +
                    '  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />\n' +
                    '</svg> ';
                deleteButtonWrapper.appendChild(deleteButton);

                categoryCommissionSettingWrapper.appendChild(newCommissionItem);

                initTagify();
                initDeleteButtons();
            });
        }

        const initDeleteButtons = () => {
            const deleteButtons = document.querySelectorAll('[data-bb-toggle="commission-remove"]');
            if (deleteButtons) {
                deleteButtons.forEach((button) => {
                    button.addEventListener('click', (event) => {
                        event.preventDefault();
                        const index = button.getAttribute('data-index');
                        const item = document.querySelector(`#commission-setting-item-${index}`);
                        if (item) {
                            item.remove();
                        }
                    });
                });
            }
        };

        // Store whitelist globally to avoid parsing it multiple times
        let categoriesWhitelist = [];

        const initTagify = () => {
            // Initialize whitelist if not already done
            if (categoriesWhitelist.length === 0) {
                const categoriesDataElement = document.querySelector('#categories-data');
                if (!categoriesDataElement) {
                    console.error('Categories data element not found');
                    return;
                }

                try {
                    const categories = JSON.parse(categoriesDataElement.value);
                    categoriesWhitelist = categories.map((item) => {
                        return {
                            value: item.name,
                            id: item.id,
                        };
                    });
                } catch (error) {
                    console.error('Error parsing categories data:', error);
                    return;
                }
            }

            // Find all tagify inputs that don't have tagify initialized
            // We need to be careful here because Tagify adds a hidden input and a div with class 'tagify'
            // but the original textarea doesn't get the 'tagify' class
            const allInputs = document.querySelectorAll('.tagify-commission-setting');
            if (allInputs && allInputs.length > 0 && categoriesWhitelist.length > 0) {
                allInputs.forEach((input) => {
                    // Check if Tagify is already initialized on this input by looking for a sibling with class 'tagify'
                    const parent = input.parentNode;
                    const hasTagify = parent && parent.querySelector('.tagify');

                    // Also check if the input has the 'tagify__input' attribute which Tagify adds
                    const hasTagifyAttr = input.hasAttribute('readonly');

                    // Only initialize if not already initialized
                    if (!hasTagify && !hasTagifyAttr && input.tagify === undefined) {
                        new Tagify(input, {
                            enforceWhitelist: true,
                            whitelist: categoriesWhitelist,
                            dropdown: {
                                enabled: 1,
                                closeOnSelect: false,
                            },
                        });
                    }
                });
            }
        };

        initTagify();
        initDeleteButtons();
    };

    document.addEventListener('DOMContentLoaded', () => {
        initCategoryCommissionSetting();
    });
})();
