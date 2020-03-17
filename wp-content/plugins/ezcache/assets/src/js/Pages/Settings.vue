<template>
    <div>
        <header class="ezcache-screen-header">
            <svg viewBox="0 0 24 24">
                <path d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.21,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.21,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.67 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z" />
            </svg>
            {{ $wp.trans('settings') }}
        </header>

        <form method="post" @submit.prevent="saveSettings">
            <TogglePanel :showSpinner="isLoading">
                <template slot="title">
                    {{ $wp.trans('cache_settings') }}
                    <span class="mx-2">
                        <button type="button" class="wpb-button wpb-button-sm wpb-button-outlined wpb-button-primary" v-text="$wp.trans('select_all')" @click.prevent="updateSettings(true, 'no_cache_known_users', 'separate_mobile_cache', 'no_cache_query_params', 'cache_clear_on_post_edit')"></button>
                        <button type="button" class="wpb-button wpb-button-sm wpb-button-outlined wpb-button-primary" v-text="$wp.trans('select_none')" @click.prevent="updateSettings(false, 'no_cache_known_users', 'separate_mobile_cache', 'no_cache_query_params', 'cache_clear_on_post_edit')"></button>
                    </span>
                </template>

                <div class="row">
                    <div class="col-12 my-2">
                        <input type="checkbox" class="checkbox" :id="$id('no_cache_known_users')" v-model="settings.no_cache_known_users" :disabled="isLoading || isSaving">
                        <label :for="$id('no_cache_known_users')" v-text="$wp.trans('no_cache_known_users')"></label>

                        <p class="description" v-text="$wp.trans('no_cache_known_users_description')"></p>
                    </div>
                    <div class="col-12 my-2">
                        <input type="checkbox" class="checkbox" :id="$id('separate_mobile_cache')" v-model="settings.separate_mobile_cache" :disabled="isLoading || isSaving">
                        <label :for="$id('separate_mobile_cache')" v-text="$wp.trans('separate_mobile_cache')"></label>

                        <p class="description" v-text="$wp.trans('separate_mobile_cache_description')"></p>
                    </div>

                    <div class="col-12 my-2">
                        <input type="checkbox" class="checkbox" :id="$id('no_cache_query_params')" v-model="settings.no_cache_query_params" :disabled="isLoading || isSaving">
                        <label :for="$id('no_cache_query_params')" v-text="$wp.trans('no_cache_query_params')"></label>

                        <p class="description" v-text="$wp.trans('no_cache_query_params_description')"></p>
                    </div>

                    <div class="col-12 my-2">
                        <input type="checkbox" class="checkbox" :id="$id('cache_clear_on_post_edit')" v-model="settings.cache_clear_on_post_edit" :disabled="isLoading || isSaving">
                        <label :for="$id('cache_clear_on_post_edit')" v-text="$wp.trans('cache_clear_on_post_edit')"></label>

                        <p class="description" v-text="$wp.trans('cache_clear_on_post_edit_description')"></p>
                    </div>
                </div>
            </TogglePanel>

            <TogglePanel :showSpinner="isLoading">
                <template slot="title">
                    {{ $wp.trans('performance_settings') }}
                    <span class="mx-2">
                        <button type="button" class="wpb-button wpb-button-sm wpb-button-outlined wpb-button-primary" v-text="$wp.trans('select_all')" @click.prevent="updateSettings(true, 'disable_wp_emoji', 'optimize_google_fonts', 'enable_webp_support', 'minify_html', 'minify_inline_js', 'minify_inline_css', 'minify_js', 'combine_head_js', 'combine_body_js', 'minify_css', 'combine_css')"></button>
                        <button type="button" class="wpb-button wpb-button-sm wpb-button-outlined wpb-button-primary" v-text="$wp.trans('select_none')" @click.prevent="updateSettings(false, 'disable_wp_emoji', 'optimize_google_fonts', 'enable_webp_support', 'minify_html', 'minify_inline_js', 'minify_inline_css', 'minify_js', 'combine_head_js', 'combine_body_js', 'minify_css', 'combine_css')"></button>
                    </span>
                </template>

                <div class="row">
                    <div class="col-12 my-2">
                        <input type="checkbox" class="checkbox" :id="$id('disable_wp_emoji')" v-model="settings.disable_wp_emoji" :disabled="isLoading || isSaving">
                        <label :for="$id('disable_wp_emoji')" v-text="$wp.trans('disable_wp_emoji')"></label>

                        <p class="description" v-text="$wp.trans('disable_wp_emoji_description')"></p>
                    </div>

                    <div class="col-12 my-2">
                        <input type="checkbox" class="checkbox" :id="$id('optimize_google_fonts')" v-model="settings.optimize_google_fonts" :disabled="isLoading || isSaving">
                        <label :for="$id('optimize_google_fonts')" v-text="$wp.trans('optimize_google_fonts')"></label>

                        <p class="description" v-text="$wp.trans('optimize_google_fonts_description')"></p>
                    </div>

                    <div class="col-12 my-2">
                        <input type="checkbox" class="checkbox" :id="$id('enable_webp_support')" v-model="settings.enable_webp_support" :disabled="isLoading || isSaving">
                        <label :for="$id('enable_webp_support')">
                            {{ $wp.trans('enable_webp_support') }}
                            <RouterLink to="/license" class="badge badge-warning" v-text="$wp.trans('requries_premium_license')"></RouterLink>
                        </label>

                        <p class="description" v-text="$wp.trans('enable_webp_support_description')"></p>
                    </div>



                    <div class="col-12 mt-2">
                        <input type="checkbox" class="checkbox" :id="$id('minify_html')" v-model="settings.minify_html" :disabled="isLoading || isSaving">
                        <label :for="$id('minify_html')" v-text="$wp.trans('minify_html')"></label>

                        <p class="description" v-text="$wp.trans('minify_html_description')"></p>
                    </div>
                    <transition name="slide-down-fade">
                        <div v-if="settings.minify_html" class="col-12 my-2 pt-2 mx-4 ezcache-setting-inner">
                            <div>
                                <input type="checkbox" class="checkbox" :id="$id('minify_html_comments')" v-model="settings.minify_html_comments" :disabled="isLoading">
                                <label :for="$id('minify_html_comments')" v-text="$wp.trans('minify_html_comments')"></label>

                                <p class="description" v-text="$wp.trans('minify_html_comments_description')"></p>
                            </div>
                            <div>
                                <input type="checkbox" class="checkbox" :id="$id('minify_inline_js')" v-model="settings.minify_inline_js" :disabled="isLoading || settings.combine_head_js || settings.combine_body_js">
                                <label :for="$id('minify_inline_js')" v-text="$wp.trans('minify_inline_js')"></label>

                                <p class="description" v-text="$wp.trans('minify_inline_js_description')"></p>
                            </div>
                            <div>
                                <input type="checkbox" class="checkbox" :id="$id('minify_inline_css')" v-model="settings.minify_inline_css" :disabled="isLoading || settings.combine_css">
                                <label :for="$id('minify_inline_css')" v-text="$wp.trans('minify_inline_css')"></label>

                                <p class="description" v-text="$wp.trans('minify_inline_css_description')"></p>
                            </div>
                        </div>
                    </transition>



                    <div class="col-12 mt-2">
                        <input type="checkbox" class="checkbox" :id="$id('minify_js')" v-model="settings.minify_js" :disabled="isLoading || isSaving">
                        <label :for="$id('minify_js')" v-text="$wp.trans('minify_js')"></label>

                        <p class="description" v-text="$wp.trans('minify_js_description')"></p>
                    </div>
                    <transition name="slide-down-fade">
                        <div v-if="settings.minify_js" class="col-12 my-2 pt-2 mx-4 ezcache-setting-inner">
                            <div >
                                <input type="checkbox" class="checkbox" :id="$id('combine_head_js')" @change="settings.combine_head_js = $event.target.checked; settings.minify_inline_js = $event.target.checked" :checked="settings.combine_head_js" :disabled="isLoading || isSaving">
                                <label :for="$id('combine_head_js')">
                                    {{ $wp.trans('combine_head_js') }}
                                    <a href="https://http2.github.io/faq/#why-is-http2-multiplexed" rel="nofollow noopener" target="_blank" class="badge badge-warning" v-if="$wp.is_https_2" v-text="$wp.trans('not_recommended_https2')"></a>
                                </label>

                                <p class="description" v-text="$wp.trans('combine_head_js_description')"></p>
                            </div>
                            <div>
                                <input type="checkbox" class="checkbox" :id="$id('combine_body_js')" @change="settings.combine_body_js = $event.target.checked; settings.minify_inline_js = $event.target.checked" :checked="settings.combine_body_js" :disabled="isLoading || isSaving">
                                <label :for="$id('combine_body_js')">
                                    {{ $wp.trans('combine_body_js') }}
                                    <a href="https://http2.github.io/faq/#why-is-http2-multiplexed" rel="nofollow noopener" target="_blank" class="badge badge-warning" v-if="$wp.is_https_2" v-text="$wp.trans('not_recommended_https2')"></a>
                                </label>

                                <p class="description" v-text="$wp.trans('combine_body_js_description')"></p>
                            </div>
                        </div>
                    </transition>


                    <div class="col-12 mt-2">
                        <input type="checkbox" class="checkbox" :id="$id('minify_css')" v-model="settings.minify_css" :disabled="isLoading || isSaving">
                        <label :for="$id('minify_css')" v-text="$wp.trans('minify_css')"></label>

                        <p class="description" v-text="$wp.trans('minify_css_description')"></p>
                    </div>
                    <transition name="slide-down-fade">
                        <div v-if="settings.minify_css" class="col-12 my-0 mb-2 pt-2 mx-4 ezcache-setting-inner">
                            <div>
                                <input type="checkbox" class="checkbox" :id="$id('combine_css')" @change="settings.combine_css = $event.target.checked; settings.minify_inline_css = $event.target.checked" :checked="settings.combine_css" :disabled="isLoading || isSaving">
                                <label :for="$id('combine_css')">
                                    {{ $wp.trans('combine_css') }}
                                    <a href="https://http2.github.io/faq/#why-is-http2-multiplexed" rel="nofollow noopener" target="_blank" class="badge badge-warning" v-if="$wp.is_https_2" v-text="$wp.trans('not_recommended_https2')"></a>
                                </label>

                                <p class="description" v-text="$wp.trans('combine_css_description')"></p>
                            </div>
                        </div>
                    </transition>
                </div>
            </TogglePanel>

            <TogglePanel :showSpinner="isLoading">
                <template slot="title">{{ $wp.trans('cache_expiry_settings') }}</template>

                <div class="row">
                    <div class="col-12 my-2">
                        <label :for="$id('cache_lifetime')" v-text="$wp.trans('cache_lifetime')"></label>
                        <select :id="$id('cache_lifetime')" v-model.number="settings.cache_lifetime" :disabled="isLoading || isSaving">
                            <option value="0" :disabled="settings.preload_enabled" v-text="$wp.trans('never_expire')"></option>
                            <option value="3600" :disabled="settings.preload_enabled" v-text="$wp.trans('n_hours').replace('%s', '1')"></option>
                            <option value="10800" :disabled="settings.preload_enabled" v-text="$wp.trans('n_hours').replace('%s', '3')"></option>
                            <option value="21600" :disabled="settings.preload_enabled" v-text="$wp.trans('n_hours').replace('%s', '6')"></option>
                            <option value="43200" :disabled="settings.preload_enabled" v-text="$wp.trans('n_hours').replace('%s', '12')"></option>
                            <option value="86400" v-text="$wp.trans('n_days').replace('%s', '1')"></option>
                            <option value="172800" v-text="$wp.trans('n_days').replace('%s', '3')"></option>
                            <option value="604800" v-text="$wp.trans('n_days').replace('%s', '7')"></option>
                            <option value="2592000" v-text="$wp.trans('n_days').replace('%s', '30')"></option>
                        </select>
                        <p class="description" v-text="$wp.trans('cache_lifetime_description')"></p>
                        <p v-if="settings.preload_enabled" class="notice notice-info" v-text="$wp.trans('cache_cache_lifetime_limited_with_preload')"></p>
                    </div>

                    <div v-if="settings.cache_lifetime > 0" class="col-12 my-2">
                        <div class="row">
                            <div class="col-12 my-2">
                                <label :for="$id('cache_expiry_interval')" v-text="$wp.trans('cache_expiry_interval')"></label>
                                <select :id="$id('cache_expiry_interval')" v-model.number="settings.cache_expiry_interval" :disabled="isLoading || isSaving">
                                    <option value="3600" v-text="$wp.trans('n_hours').replace('%s', '1')"></option>
                                    <option value="10800" v-text="$wp.trans('n_hours').replace('%s', '3')"></option>
                                    <option value="21600" v-text="$wp.trans('n_hours').replace('%s', '6')"></option>
                                    <option value="43200" v-text="$wp.trans('n_hours').replace('%s', '12')"></option>
                                    <option value="86400" v-text="$wp.trans('n_days').replace('%s', '1')"></option>
                                </select>
                                <p class="description" v-text="$wp.trans('cache_expiry_interval_description')"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </TogglePanel>

            <p class="submit">
                <button type="submit" class="wpb-button wpb-button-lg wpb-button-primary" :disabled="isLoading || isSaving">
                    <Loader v-if="isSaving" color="#fff"></Loader>
                    <svg v-else viewBox="0 0 24 24">
                        <path d="M15,9H5V5H15M12,19A3,3 0 0,1 9,16A3,3 0 0,1 12,13A3,3 0 0,1 15,16A3,3 0 0,1 12,19M17,3H5C3.89,3 3,3.9 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V7L17,3Z" />
                    </svg>
                    {{ $wp.trans('save_settings') }}
                </button>
            </p>
        </form>
    </div>
</template>

<script>
    import Loader from "../Components/Loader";
    import TogglePanel from "../Components/TogglePanel";
    import API from "../Utilities/Api";

    export default {
        components: {Loader ,TogglePanel},
        data() {
            return {
                isLoading: true,
                isSaving: false,

                settings: {
                    no_cache_known_users: null,
                    separate_mobile_cache: null,
                    no_cache_query_params: null,
                    cache_clear_on_post_edit: null,
                    disable_wp_emoji: null,
                    optimize_google_fonts: null,
                    minify_html: null,
                    minify_html_comments: null,
                    minify_inline_js: null,
                    minify_inline_css: null,
                    minify_js: null,
                    minify_css: null,
                    combine_head_js: null,
                    combine_body_js: null,
                    combine_css: null,
                    enable_webp_support: null,
                    cache_lifetime: null,
                    cache_expiry_interval: null,
                }
            };
        },
        async mounted() {
            this.isLoading = true;

            try {
                let response = await API.get('settings');
                this.settings = Object.assign(this.settings, response.data.data)
            } catch(error) {
                console.error(error);
            }

            this.isLoading = false;
        },
        methods: {
            async saveSettings() {
                this.isSaving = true;

                try {
                    await API.patch('settings', this.settings);
                    this.toast(this.$wp.trans('settings_saved'), 'success')
                } catch (e) {
                    console.error(e);
                    this.toast(this.$wp.trans('error_saving_settings') + ':' + e.message, 'error');
                }

                this.isSaving = false;
            },
            updateSettings(value, ...fields) {
                for(let field of fields) {
                    this.settings[field] = value;
                }
            }
        }
    };
</script>

<style>
    .widget + .widget {
        margin-top: 40px;
    }
</style>
