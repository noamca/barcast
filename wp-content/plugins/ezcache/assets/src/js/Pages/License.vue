<template>
    <div>
        <header class="ezcache-screen-header">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22,18V22H18V19H15V16H12L9.74,13.74C9.19,13.91 8.61,14 8,14A6,6 0 0,1 2,8A6,6 0 0,1 8,2A6,6 0 0,1 14,8C14,8.61 13.91,9.19 13.74,9.74L22,18M7,5A2,2 0 0,0 5,7A2,2 0 0,0 7,9A2,2 0 0,0 9,7A2,2 0 0,0 7,5Z" />
            </svg>
            {{ $wp.trans('license') }}
        </header>

        <form method="post" @submit.prevent="saveSettings">
            <TogglePanel :showSpinner="isLoading">
                <template slot="title">{{ $wp.trans('license_key') }}</template>

                <div>
                    <label :for="$id('license_key')" class="screen-reader-text">{{ $wp.trans('license_key') }}</label>
                    <p class="font-weight-normal mb-0" v-text="$wp.trans('license_key_description')"></p>
                </div>

                <div class="row">
                    <div class="col my-2">
                        <input v-if="!isLicenseSet && !licenseData.is_upress" :id="$id('license_key')"
                               class="form-control ltr my-2"
                               :class="{ 'is-invalid': (licenseData.status === 'error' || licenseData.licence_status === 'inactive' || (licenseData.status === 'success' && !licenseData.licence_status)), 'is-valid': licenseData.licence_status === 'active' }"
                               v-model="licenseKey"
                               placeholder="xxxx-xxxx-xxxx-xxxx"
                               :disabled="isLicenseSet"
                               autocomplete="off"
                        />
                        <div v-else>
                            <div style="display: flex; flex-direction: row; align-items: center; font-size: 1.2em;">
                                <div class="my-auto mx-2 text-center">
                                    <div v-if="licenseData.status === 'error' || licenseData.licence_status === 'inactive' || (licenseData.status === 'success' && !licenseData.licence_status)">
                                        <svg style="width:2em;height:2em" viewBox="0 0 24 24">
                                            <path fill="#C4243A" d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12C4,13.85 4.63,15.55 5.68,16.91L16.91,5.68C15.55,4.63 13.85,4 12,4M12,20A8,8 0 0,0 20,12C20,10.15 19.37,8.45 18.32,7.09L7.09,18.32C8.45,19.37 10.15,20 12,20Z" />
                                        </svg>
                                    </div>
                                    <div v-else-if="licenseData.licence_status === 'active'">
                                        <svg style="width:2em;height:2em" viewBox="0 0 24 24">
                                            <path fill="#1A70A6" d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
                                        </svg>
                                    </div>
                                </div>

                                <p style="font-size: 1em;" v-text="licenseData.is_upress ? '•••• •••• •••• ••••' : licenseKey"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-auto my-auto d-flex align-items-center" v-if="!isLoading">
                        <svg v-if="licenseData.is_upress" style="width: auto; height: 5em; margin-top: -1em; opacity: 0.3;" viewBox="0 0 941 660" version="1.1" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" xmlns:serif="http://www.serif.com/" fill-rule="evenodd" clip-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2">
                            <path d="M572.044 491.799V331.118H507.03l44.633 75.08h-50.665v100.043c0 16.46-4.994 28.492-14.966 36.017-9.972 7.587-21.136 11.38-33.468 11.38-12.332 0-23.342-3.793-32.954-11.38-9.602-7.525-14.442-19.557-14.442-36.017V406.198h-51.609l44.641-75.08h-65.048v160.681c0 8.05.455 15.421 1.218 22.553-14.511 5.569-30.138 8.778-46.597 8.778-71.252 0-129.049-57.788-129.049-129.049 0-69.647 55.136-126.182 124.157-128.869 27.29-90.629 111.303-156.689 210.831-156.689 92.012 0 170.489 56.637 203.365 136.763h66.464C727.739 130.036 620.565 45.988 493.712 45.988c-110.049 0-210.256 65.452-256.201 163.196-82.005 21.942-141.363 96.353-141.363 184.897 0 105.621 85.917 191.625 191.625 191.625 21.196 0 47.327-5.577 67.709-12.58 4.255 4.831 8.838 9.088 14.193 12.426 24.423 15.078 51.344 23.282 80.804 24.681 30.293 0 58.045-7.586 83.472-22.664 25.367-15.112 38.093-47.027 38.093-95.77" fill="#1188ca" fill-rule="nonzero"/>
                            <path d="M839.05 333.891c-5.354-45.337-38.917-79.791-81.902-92.243-50.974-14.838-116.812-9.611-147.233 39.406-9.08 14.657-16.091 32.901-15.327 50.365 1.862 46.872.738 93.899-.094 140.745-.721 42.711-5.655 89.523-35.175 122.992-9.372 10.554-22.003 20.741-36.078 24.233 44.496 0 95.83-9.525 123.797-47.73 9.869-13.498 17.043-30.902 17.163-47.851 40.926 8.05 89.824 5.347 125.986-16.828 28.027-17.163 45.344-46.572 51.317-78.486 5.132-27.874 2.352-56.295-1.201-84.194l-1.253-10.409zM728.357 473.718c-19.788.918-41.552 3.364-61.315.086-1.631-.301-.429-25.35-.429-27.59v-112.1c0-9.148 3.33-18.854 8.23-26.5 14.657-22.672 43.902-21.256 66.876-14.074 23.599 7.466 33.047 25.092 34.909 48.803 1.717 22.544.498 45.302.652 67.923.335 30.594-14.931 61.898-48.923 63.452" fill="#000"/>
                        </svg>
                        <fragment v-else>
                            <button v-if="!licenseData.key" type="submit" class="wpb-button wpb-button-lg wpb-button-primary" :disabled="isLoading || isSaving">
                                <Loader v-if="isSaving" color="#fff"></Loader>
                                <svg v-else viewBox="0 0 24 24">
                                    <path d="M22,18V22H18V19H15V16H12L9.74,13.74C9.19,13.91 8.61,14 8,14A6,6 0 0,1 2,8A6,6 0 0,1 8,2A6,6 0 0,1 14,8C14,8.61 13.91,9.19 13.74,9.74L22,18M7,5A2,2 0 0,0 5,7A2,2 0 0,0 7,9A2,2 0 0,0 9,7A2,2 0 0,0 7,5Z" />
                                </svg>
                                {{ $wp.trans('activate_license') }}
                            </button>
                            <button v-if="licenseData.key" type="button" class="wpb-button wpb-button-lg wpb-button-danger" @click.prevent="clearLicense" :disabled="isLoading || isSaving">
                                <Loader v-if="isSaving" color="#fff"></Loader>
                                <svg v-else viewBox="0 0 24 24">
                                    <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12C4,13.85 4.63,15.55 5.68,16.91L16.91,5.68C15.55,4.63 13.85,4 12,4M12,20A8,8 0 0,0 20,12C20,10.15 19.37,8.45 18.32,7.09L7.09,18.32C8.45,19.37 10.15,20 12,20Z" />
                                </svg>
                                {{ $wp.trans('deactivate_license') }}
                            </button>

                            <a v-if="licenseData.is_trial || !licenseData.key" :href="$wp.trans('ezcache_pricing_link')" class="wpb-button wpb-button-lg" target="_blank" rel="nofollow noopener">
                                <svg viewBox="0 0 24 24">
                                    <path d="M11,9H13V6H16V4H13V1H11V4H8V6H11M7,18A2,2 0 0,0 5,20A2,2 0 0,0 7,22A2,2 0 0,0 9,20A2,2 0 0,0 7,18M17,18A2,2 0 0,0 15,20A2,2 0 0,0 17,22A2,2 0 0,0 19,20A2,2 0 0,0 17,18M7.17,14.75L7.2,14.63L8.1,13H15.55C16.3,13 16.96,12.59 17.3,11.97L21.16,4.96L19.42,4H19.41L18.31,6L15.55,11H8.53L8.4,10.73L6.16,6L5.21,4L4.27,2H1V4H3L6.6,11.59L5.25,14.04C5.09,14.32 5,14.65 5,15A2,2 0 0,0 7,17H19V15H7.42C7.29,15 7.17,14.89 7.17,14.75Z" />
                                </svg>
                                {{ $wp.trans('purchase_license') }}
                            </a>
                        </fragment>
                    </div>
                </div>
            </TogglePanel>
        </form>

        <TogglePanel :showSpinner="isLoading || isSaving">
            <template slot="title">{{ $wp.trans('license_details') }}</template>
            <LicenseDetails :license="licenseData" :isLoading="isLoading || isSaving"></LicenseDetails>
        </TogglePanel>
    </div>
</template>

<script>
    import Loader from "../Components/Loader";
    import TogglePanel from "../Components/TogglePanel";
    import LicenseDetails from "../Components/LicenseDetails";
    import API from "../Utilities/Api";

    export default {
        components: {LicenseDetails, Loader, TogglePanel},
        data() {
            return {
                isLoading: true,
                isSaving: false,
                licenseKey: '',
                isLicenseSet: false,
                licenseData: {
                    status: '',
                    message: '',
                    licence_status: '',
                    licence_start: '',
                    licence_expire: '',
                    is_trial: '',
                    is_trial_started: '',
                    uses_left: '',
                    is_upress: false,
                }
            };
        },
        async mounted() {
            try {
                let response = await API.get('license');
                this.licenseKey = response.data.data.key;
                this.isLicenseSet = !! this.licenseKey;
                this.licenseData = response.data.data;
            } catch(error) {
                console.error(error);
            }

            this.isLoading = false;
        },
        methods: {
            async saveSettings() {
                this.isSaving = true;

                try {
                    let response = await API.patch('license', {licenseKey: this.licenseKey});
                    this.isSaving = false;

                    this.licenseData.status = response.data.data.status ? response.data.data.status : '';
                    this.licenseData.message = response.data.data.message ? response.data.data.message : '';

                    if (response.data.success) {
                        this.licenseKey = response.data.data.key;
                        this.isLicenseSet = !!this.licenseKey;
                        this.licenseData = response.data.data;
                        this.toast(this.$wp.trans('settings_saved'), 'success');
                        return;
                    }

                    this.toast((response.data.data.length ? response.data.data[0].message : this.$wp.trans('error_saving_settings')), 'error');
                } catch (e) {
                    console.error(e);
                    this.isSaving = false;
                    this.toast(this.$wp.trans('error_saving_settings') + ':' + e.message, 'error');
                }
            },

            async clearLicense() {
                this.isSaving = true;
                this.licenseKey = '';

                try {
                    let response = await API.delete('license');
                    this.isSaving = false;

                    if(response.data.success) {
                        this.licenseData = response.data.data;
                        this.isLicenseSet = false;
                        this.licenseData = response.data.data;
                        return;
                    }

                    this.toast((response.data.data.length ? response.data.data[0].message : this.$wp.trans('error_saving_settings')), 'error');
                } catch(e) {
                    this.isSaving = false;
                    console.error(e);
                    this.toast(this.$wp.trans('error_saving_settings') + ':' + e.message, 'error');
                }
            }
        }
    };
</script>
