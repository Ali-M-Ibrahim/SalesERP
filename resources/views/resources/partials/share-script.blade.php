@can('resources.share')

    @push('scripts')

        <script>

            document.addEventListener(
                'DOMContentLoaded',
                function () {

                    const modal =
                        document.getElementById(
                            'resource-share-modal'
                        );


                    const resourceName =
                        document.getElementById(
                            'share-resource-name'
                        );


                    const customer =
                        document.getElementById(
                            'share-customer'
                        );


                    const confirmButton =
                        document.getElementById(
                            'confirm-resource-share'
                        );


                    const csrfToken =
                        document
                            .querySelector(
                                'meta[name="csrf-token"]'
                            )
                            ?.content;


                    let shareUrl =
                        null;


                    function openModal() {

                        modal
                            ?.classList
                            .remove('hidden');


                        document.body.classList.add(
                            'overflow-hidden'
                        );

                    }


                    function closeModal() {

                        modal
                            ?.classList
                            .add('hidden');


                        document.body.classList.remove(
                            'overflow-hidden'
                        );


                        if (customer) {

                            customer.value =
                                '';

                        }


                        shareUrl =
                            null;

                    }


                    /*
                     * Open share modal.
                     */
                    document.addEventListener(
                        'click',
                        function (event) {

                            const button =
                                event.target.closest(
                                    '.open-resource-share'
                                );


                            if (!button) {
                                return;
                            }


                            shareUrl =
                                button.dataset.url;


                            resourceName.textContent =
                                button.dataset.name;


                            openModal();

                        }
                    );


                    /*
                     * Close.
                     */
                    document
                        .getElementById(
                            'close-resource-share'
                        )
                        ?.addEventListener(
                            'click',
                            closeModal
                        );


                    document
                        .getElementById(
                            'cancel-resource-share'
                        )
                        ?.addEventListener(
                            'click',
                            closeModal
                        );


                    /*
                     * Share.
                     */
                    confirmButton?.addEventListener(
                        'click',
                        async function () {

                            if (!customer.value) {

                                alert(
                                    'Please select a customer.'
                                );

                                return;

                            }


                            if (!shareUrl) {

                                return;

                            }


                            const button =
                                this;


                            const originalHTML =
                                button.innerHTML;


                            /*
                             * Open blank window immediately.
                             *
                             * Mobile browsers may block
                             * window.open() if it happens
                             * only after AJAX finishes.
                             */
                            const whatsappWindow =
                                window.open(
                                    '',
                                    '_blank'
                                );


                            button.disabled =
                                true;


                            button.innerHTML = `
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    Preparing...
                `;


                            try {

                                const response =
                                    await fetch(
                                        shareUrl,
                                        {
                                            method:
                                                'POST',

                                            headers: {

                                                'Accept':
                                                    'application/json',

                                                'Content-Type':
                                                    'application/json',

                                                'X-Requested-With':
                                                    'XMLHttpRequest',

                                                'X-CSRF-TOKEN':
                                                csrfToken

                                            },

                                            body:
                                                JSON.stringify({

                                                    customer_id:
                                                    customer.value

                                                })

                                        }
                                    );


                                const contentType =
                                    response.headers.get(
                                        'content-type'
                                    );


                                if (
                                    !contentType ||
                                    !contentType.includes(
                                        'application/json'
                                    )
                                ) {

                                    console.error(
                                        await response.text()
                                    );


                                    throw new Error(
                                        'The server returned an invalid response.'
                                    );

                                }


                                const data =
                                    await response.json();


                                if (!response.ok) {

                                    throw new Error(
                                        data.message ||
                                        'Unable to share resource.'
                                    );

                                }


                                /*
                                 * Navigate popup to WhatsApp.
                                 */
                                if (
                                    whatsappWindow
                                ) {

                                    whatsappWindow.location.href =
                                        data.whatsapp_url;

                                } else {

                                    window.location.href =
                                        data.whatsapp_url;

                                }


                                closeModal();


                                button.disabled =
                                    false;


                                button.innerHTML =
                                    originalHTML;

                            }
                            catch (error) {

                                console.error(error);


                                whatsappWindow
                                    ?.close();


                                alert(
                                    error.message
                                );


                                button.disabled =
                                    false;


                                button.innerHTML =
                                    originalHTML;

                            }

                        }
                    );

                    $('#share-customer').select2({

                        width: '100%',

                        placeholder: 'Search customer...',

                        allowClear: true,
                    });

                }
            );

        </script>

    @endpush

@endcan
