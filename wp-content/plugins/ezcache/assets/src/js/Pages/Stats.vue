<template>
    <div>
        <header class="ezcache-screen-header">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M3,22V8H7V22H3M10,22V2H14V22H10M17,22V14H21V22H17Z" />
            </svg>
            {{ $wp.trans('stats') }}
        </header>


        <div v-if="error">
            <p class="notice notice-error inline">{{ error }}</p>
        </div>
        <div v-else class="row">
            <div class="col-12 col-xl-4 my-2">
                <TogglePanel>
                    <div class="stats-block">
                        <svg class="stats-block-icon" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
  <path d="M93 14.7H7c-2.6 0-4.8 2.1-4.8 4.8V62c0 2.6 2.1 4.8 4.8 4.8h39v13.1H35.6c-1.5 0-2.8 1.2-2.8 2.8s1.2 2.8 2.8 2.8h28.8c1.5 0 2.8-1.2 2.8-2.8s-1.2-2.8-2.8-2.8H54V66.8h39c2.6 0 4.8-2.1 4.8-4.8V19.4c0-2.6-2.2-4.7-4.8-4.7zm-.7 46.6H7.8V20.2h84.5v41.1z"/>
                        </svg>
                        <div>
                            <p class="stats-block-title" v-text="$wp.trans('desktop')"></p>
                            <p class="stats-block-size ltr">
                                <Loader v-if="isLoading"></Loader>
                                <span v-else>
                                    <strong>{{ stats.desktop_count }}</strong> / {{ prettyBytes(stats.desktop_size) }}
                                </span>
                            </p>
                            <p class="stats-block-description" v-text="$wp.trans('desktop_stats_description')"></p>
                        </div>
                    </div>
                </TogglePanel>
            </div>

            <div class="col-12 col-xl-4 my-2">
                <TogglePanel>
                    <div class="stats-block">
                        <svg class="stats-block-icon" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
  <path d="M74.6 2.9H25.4c-2.6 0-4.7 2.1-4.7 4.7v84.9c0 2.6 2.1 4.7 4.7 4.7h49.3c2.6 0 4.7-2.1 4.7-4.7V7.6c-.1-2.6-2.2-4.7-4.8-4.7zm-.8 88.7H26.2V8.4h47.7l-.1 83.2z"/>
                                <path d="M46.4 16.9h7.5c1.1 0 2-.9 2-2s-.9-2-2-2h-7.5c-1.1 0-2 .9-2 2s.9 2 2 2z"/>
                                <circle cx="62.9" cy="14.9" r="2.5"/>
                                <circle cx="50" cy="83.1" r="3.8"/>
                        </svg>
                        <div>
                            <p class="stats-block-title" v-text="$wp.trans('mobile')"></p>
                            <p class="stats-block-size ltr">
                                <Loader v-if="isLoading"></Loader>
                                <span v-else>
                                    <strong>{{ stats.mobile_count }}</strong> / {{ prettyBytes(stats.mobile_size) }}
                                </span>
                            </p>
                            <p class="stats-block-description" v-text="$wp.trans('mobile_stats_description')"></p>
                        </div>
                    </div>
                </TogglePanel>
            </div>

            <div class="col-12 col-xl-4 my-2">
                <TogglePanel>
                    <div class="stats-block">
                        <svg class="stats-block-icon" viewBox="0 0 100 100" version="1.1" xmlns="http://www.w3.org/2000/svg">
  <path d="M82 14.8H65.8C65.3 8 59.5 2.6 52.5 2.6H47c-6.9 0-12.6 5.4-13.1 12.2H18c-2.4 0-4.2 1.8-4.2 4.2l3.7 64.4c.4 7.8 6.8 13.9 14.7 13.9h35.5c7.9 0 14.3-6 14.8-13.9l3.9-64.2V19c-.1-2.3-2-4.2-4.4-4.2zM47 8.1h5.5c3.9 0 7.2 3 7.7 6.7H39.4c.5-3.8 3.7-6.7 7.6-6.7zm29.9 75c-.3 5-4.4 8.8-9.3 8.8H32.1c-4.9 0-8.9-3.8-9.2-8.7l-3.6-62.8h61.4l-3.8 62.7z"/>
                                <path d="M60.5 41.4v20.9c0 1.5 1.2 2.8 2.8 2.8s2.8-1.2 2.8-2.8V41.4c0-1.5-1.2-2.8-2.8-2.8s-2.8 1.3-2.8 2.8z"/>
                                <path d="M36.4 38.6c-1.5 0-2.8 1.2-2.8 2.8v20.9c0 1.5 1.2 2.8 2.8 2.8s2.8-1.2 2.8-2.8V41.4c0-1.5-1.2-2.8-2.8-2.8z"/>
                                <path d="M50.4 34.6c-1.5 0-2.8 1.2-2.8 2.8v28.9c0 1.5 1.2 2.8 2.8 2.8s2.8-1.2 2.8-2.8V37.4c0-1.5-1.2-2.8-2.8-2.8z"/>
                        </svg>
                        <div>
                            <p class="stats-block-title" v-text="$wp.trans('expired')"></p>
                            <p class="stats-block-size ltr">
                                <Loader v-if="isLoading"></Loader>
                                <span v-else>
                                    <strong>{{ expired_count }}</strong> / {{ prettyBytes(expired_size) }}
                                </span>
                            </p>
                            <p class="stats-block-description" v-text="$wp.trans('expired_stats_description')"></p>
                        </div>
                    </div>
                </TogglePanel>
            </div>

            <div class="col-12 col-xl-4 my-2">
                <TogglePanel>
                    <div class="stats-block">
                        <svg class="stats-block-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                            <path d="M27.8 27.4c-1.1-1.1-2.8-1.1-3.9 0L4 47.3c-.7.8-1.1 1.7-1.1 2.7 0 1 .4 1.9 1.1 2.7l19.9 19.9c.5.5 1.2.8 1.9.8s1.4-.3 1.9-.8c1.1-1.1 1.1-2.8 0-3.9L9.1 50l18.7-18.7c1.1-1.1 1.1-2.8 0-3.9z"/>
                            <path d="M96 47.3L76.1 27.4c-1.1-1.1-2.8-1.1-3.9 0s-1.1 2.8 0 3.9L90.9 50 72.2 68.7c-1.1 1.1-1.1 2.8 0 3.9.5.5 1.2.8 1.9.8s1.4-.3 1.9-.8l20-19.9c.7-.7 1.1-1.6 1.1-2.7 0-1-.4-1.9-1.1-2.7z"/>
                            <path d="M60.3 30.7c-1.4-.7-3-.1-3.7 1.3L38.5 69.2c-.7 1.4-.1 3 1.3 3.7.4.2.8.3 1.2.3 1 0 2-.6 2.5-1.5l18.1-37.3c.6-1.4 0-3.1-1.3-3.7z"/>
                        </svg>
                        <div>
                            <p class="stats-block-title" v-text="$wp.trans('javascript')"></p>
                            <p class="stats-block-size ltr">
                                <Loader v-if="isLoading"></Loader>
                                <span v-else>
                                    <strong>{{ stats.js_count }}</strong> / {{ prettyBytes(stats.js_size) }}
                                </span>
                            </p>
                            <p class="stats-block-description" v-text="$wp.trans('javascript_stats_description')"></p>
                        </div>
                    </div>
                </TogglePanel>
            </div>
            <div class="col-12 col-xl-4 my-2">
                <TogglePanel>
                    <div class="stats-block">
                        <svg class="stats-block-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                            <path d="M97.5 36.1L64 2.6c-.8-.8-1.8-1.2-3-1.2-1.1 0-2.2.4-3 1.2L30.3 30.5c-7.1 7.1-7.7 17.8-1.9 25.5l-24 24C.1 84.3.1 91.2 4.4 95.4c2.1 2.1 4.9 3.2 7.7 3.2s5.6-1.1 7.7-3.2l23.9-23.9c3.5 2.7 7.7 4.1 11.9 4.1 5 0 10-1.9 13.9-5.7l28-27.9c.8-.8 1.2-1.8 1.2-3s-.4-2.1-1.2-2.9zM16 91.5c-2.1 2.1-5.5 2.1-7.7 0-2.1-2.1-2.1-5.5 0-7.7L32 60.1l7.7 7.7L16 91.5zM65.8 66c-5.6 5.4-14.5 5.5-19.9 0L34.2 54.3c-5.6-5.6-5.6-14.3 0-19.9l4.6-4.6 31.6 31.6-4.6 4.6zm8.4-8.5L42.6 25.9 61.1 7.5l31.6 31.6-18.5 18.4z"/>
                            <path d="M42.2 43.1c-2 0-3.6 1.6-3.6 3.6s1.6 3.6 3.6 3.6 3.6-1.6 3.6-3.6-1.6-3.6-3.6-3.6z"/>
                            <path d="M53.3 54.2c-2 0-3.6 1.6-3.6 3.6s1.6 3.6 3.6 3.6 3.6-1.6 3.6-3.6-1.6-3.6-3.6-3.6z"/>
                        </svg>
                        <div>
                            <p class="stats-block-title" v-text="$wp.trans('css')"></p>
                            <p class="stats-block-size ltr">
                                <Loader v-if="isLoading"></Loader>
                                <span v-else>
                                    <strong>{{ stats.css_count }}</strong> / {{ prettyBytes(stats.css_size) }}
                                </span>
                            </p>
                            <p class="stats-block-description" v-text="$wp.trans('css_stats_description')"></p>
                        </div>
                    </div>
                </TogglePanel>
            </div>
            <div class="col-12 col-xl-4 my-2">
                <TogglePanel>
                    <div class="stats-block">
                        <svg class="stats-block-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                            <path d="M87.4 2.2H12.6c-2.4 0-4.2 1.8-4.2 4.1v87.4c0 2.4 1.8 4.1 4.2 4.1h74.8c2.3 0 4.2-2 4.2-4.3V6.3c0-2.4-1.8-4.1-4.2-4.1zm-1.3 5.5V52c-1.1.7-2 1.3-3 2-2.3 1.5-4.5 3.5-6.6 5.4-5.9 5.2-11.5 10.2-18.9 7.4-1.5-.5-2.9-1.8-4.3-3l-.7-.6c-3.7-3-7.9-6.5-13-7.9-8.6-2.7-16.3 2.3-23.1 6.6-.8.5-1.7 1.1-2.5 1.6V7.7h72.1zM13.9 92.3V69.8c1.8-1 3.6-2.2 5.4-3.3 6.1-3.9 12.3-7.9 18.6-6 4 1.2 7.6 4.1 11.1 6.9l.5.4c1.7 1.5 3.7 3.2 6.1 4.1 2.2.8 4.2 1.2 6.2 1.2 7.5 0 13.4-5.2 18.3-9.6 2-1.8 4-3.5 6-4.9v33.8H13.9z"/>
                            <path d="M64.3 44.7c7.3 0 13.1-5.9 13.1-13.1s-5.9-13.2-13.1-13.2-13.2 5.9-13.2 13.2S57 44.7 64.3 44.7zm0-20.9c4.2 0 7.6 3.4 7.6 7.7s-3.4 7.6-7.6 7.6c-4.2 0-7.7-3.4-7.7-7.6s3.5-7.7 7.7-7.7z"/>
                        </svg>
                        <div>
                            <p class="stats-block-title" v-text="$wp.trans('webp_images')"></p>
                            <p class="stats-block-size ltr">
                                <Loader v-if="isLoading"></Loader>
                                <span v-else>
                                    <strong>{{ stats.webp_images }}</strong> / {{ prettyBytes(stats.webp_images_size) }}
                                </span>
                            </p>
                            <p class="stats-block-description" v-text="$wp.trans('webp_stats_description')"></p>
                        </div>
                    </div>
                </TogglePanel>
            </div>
        </div>
    </div>
</template>

<script>
    import * as prettyBytes from 'pretty-bytes'
    import Loader from "../Components/Loader";
    import TogglePanel from "../Components/TogglePanel";
    import API from "../Utilities/Api";

    export default {
        components: {Loader, TogglePanel},
        data() {
            return {
                isLoading: true,
                stats: {
                    mobile_count: 0,
                    desktop_count: 0,
                    mobile_expired_count: 0,
                    desktop_expired_count: 0,
                    mobile_size: 0,
                    desktop_size: 0,
                    mobile_expired_size: 0,
                    desktop_expired_size: 0,
                    raw_data: 0,
                    css_size: 0,
                    css_count: 0,
                    js_count: 0,
                    js_size: 0,
                    css_expired_count: 0,
                    css_expired_size: 0,
                    js_expired_count: 0,
                    js_expired_size: 0,
                    webp_images: 0,
                    webp_images_original_size: 0,
                    webp_images_size: 0
                },
                error: null
            };
        },
        computed: {
            expired_count() {
                return this.stats.desktop_expired_count + this.stats.mobile_expired_count + this.stats.js_expired_count + this.stats.css_expired_count;
            },
            expired_size() {
                return this.stats.desktop_expired_size + this.stats.mobile_expired_size + this.stats.js_expired_size + this.stats.css_expired_size;
            }
        },
        beforeDestroy() {
            this.$eventBus.$off('wpb.cache_cleared', this.onClearCache);
        },
        mounted() {
            this.$eventBus.$on('wpb.cache_cleared', this.onClearCache);
            this.fetchStats();
        },
        methods: {
            prettyBytes,
            onClearCache() {
                this.stats = {
                    mobile_count: 0,
                    desktop_count: 0,
                    mobile_expired_count: 0,
                    desktop_expired_count: 0,
                    mobile_size: 0,
                    desktop_size: 0,
                    mobile_expired_size: 0,
                    desktop_expired_size: 0,
                    raw_data: 0,
                    css_size: 0,
                    css_count: 0,
                    js_count: 0,
                    js_size: 0,
                    css_expired_count: 0,
                    css_expired_size: 0,
                    js_expired_count: 0,
                    js_expired_size: 0,
                    webp_images: 0,
                    webp_images_original_size: 0,
                    webp_images_size: 0
                };
            },
            async fetchStats() {
                this.isLoading = true;

                try {
                    let response = await API.get('cache');
                    if (response.data.success) {
                        this.stats = Object.assign(this.stats, response.data.data);
                    } else {
                        this.error = response.data.data.error;
                    }
                } catch (e) {
                    console.error(e);
                    this.error = e;
                }

                this.isLoading = false
            }
        }
    };
</script>
