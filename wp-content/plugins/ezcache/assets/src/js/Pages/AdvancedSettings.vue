<template>
    <div>
        <header class="ezcache-screen-header">
            <svg viewBox="0 0 24 24">
                <path d="M22.7,19L13.6,9.9C14.5,7.6 14,4.9 12.1,3C10.1,1 7.1,0.6 4.7,1.7L9,6L6,9L1.6,4.7C0.4,7.1 0.9,10.1 2.9,12.1C4.8,14 7.5,14.5 9.8,13.6L18.9,22.7C19.3,23.1 19.9,23.1 20.3,22.7L22.6,20.4C23.1,20 23.1,19.3 22.7,19Z" />
            </svg>
            {{ $wp.trans('advanced_settings') }}
        </header>

        <form method="post" @submit.prevent="saveSettings">
            <TogglePanel :showSpinner="isLoading">
                <template slot="title">{{ $wp.trans('cache_bypass_settings') }}</template>

                <div class="row">
                    <div class="col-12 my-2">
                        <label v-text="$wp.trans('bypass_cache_title')"></label>
                        <span class="mx-2">
                            <button type="button" class="wpb-button wpb-button-sm wpb-button-outlined wpb-button-primary" v-text="$wp.trans('select_all')" @click.prevent="updateSettings(true, 'single', 'pages', 'frontpage', 'home', 'archives', 'tag', 'category', 'feed', 'search', 'author')"></button>
                            <button type="button" class="wpb-button wpb-button-sm wpb-button-outlined wpb-button-primary" v-text="$wp.trans('select_none')" @click.prevent="updateSettings(false, 'single', 'pages', 'frontpage', 'home', 'archives', 'tag', 'category', 'feed', 'search', 'author')"></button>
                        </span>
                    </div>

                    <div class="col-12 mt-1">
                        <input type="checkbox" class="checkbox" :id="$id('bypass_cache_single')" v-model="settings.bypass_cache.single" :disabled="isLoading || isSaving">
                        <label class="font-weight-normal" :for="$id('bypass_cache_single')" v-text="$wp.trans('bypass_cache_single')">Single Posts</label>
                    </div>
                    <div class="col-12 mt-1">
                        <input type="checkbox" class="checkbox" :id="$id('bypass_cache_pages')" v-model="settings.bypass_cache.pages" :disabled="isLoading || isSaving">
                        <label class="font-weight-normal" :for="$id('bypass_cache_pages')" v-text="$wp.trans('bypass_cache_pages')">Pages</label>
                    </div>
                    <div class="col-12 mt-1">
                        <input type="checkbox" class="checkbox" :id="$id('bypass_cache_frontpage')" v-model="settings.bypass_cache.frontpage" :disabled="isLoading || isSaving">
                        <label class="font-weight-normal" :for="$id('bypass_cache_frontpage')" v-text="$wp.trans('bypass_cache_frontpage')">Front Page</label>
                    </div>
                    <div class="col-12 mt-1">
                        <input type="checkbox" class="checkbox" :id="$id('bypass_cache_home')" v-model="settings.bypass_cache.home" :disabled="isLoading || isSaving">
                        <label class="font-weight-normal" :for="$id('bypass_cache_home')" v-text="$wp.trans('bypass_cache_home')">Home</label>
                    </div>
                    <div class="col-12 mt-1">
                        <input type="checkbox" class="checkbox" :id="$id('bypass_cache_archives')" v-model="settings.bypass_cache.archives" :disabled="isLoading || isSaving">
                        <label class="font-weight-normal" :for="$id('bypass_cache_archives')" v-text="$wp.trans('bypass_cache_archives')">Archives</label>
                    </div>
                    <div class="col-12 mt-1">
                        <input type="checkbox" class="checkbox" :id="$id('bypass_cache_tag')" v-model="settings.bypass_cache.tag" :disabled="isLoading || isSaving">
                        <label class="font-weight-normal" :for="$id('bypass_cache_tag')" v-text="$wp.trans('bypass_cache_tag')">Tags</label>
                    </div>
                    <div class="col-12 mt-1">
                        <input type="checkbox" class="checkbox" :id="$id('bypass_cache_category')" v-model="settings.bypass_cache.category" :disabled="isLoading || isSaving">
                        <label class="font-weight-normal" :for="$id('bypass_cache_category')" v-text="$wp.trans('bypass_cache_category')">Categories</label>
                    </div>
                    <div class="col-12 mt-1">
                        <input type="checkbox" class="checkbox" :id="$id('bypass_cache_feed')" v-model="settings.bypass_cache.feed" :disabled="isLoading || isSaving">
                        <label class="font-weight-normal" :for="$id('bypass_cache_feed')" v-text="$wp.trans('bypass_cache_feed')">Feeds</label>
                    </div>
                    <div class="col-12 mt-1">
                        <input type="checkbox" class="checkbox" :id="$id('bypass_cache_search')" v-model="settings.bypass_cache.search" :disabled="isLoading || isSaving">
                        <label class="font-weight-normal" :for="$id('bypass_cache_search')" v-text="$wp.trans('bypass_cache_search')">Search Results</label>
                    </div>
                    <div class="col-12 mt-1">
                        <input type="checkbox" class="checkbox" :id="$id('bypass_cache_author')" v-model="settings.bypass_cache.author" :disabled="isLoading || isSaving">
                        <label class="font-weight-normal" :for="$id('bypass_cache_author')" v-text="$wp.trans('bypass_cache_author')">Author Pages</label>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 my-2">
                        <label :for="$id('rejected_uri')">
                            {{ $wp.trans('rejected_uri') }}<br>
                            <span class="font-weight-normal" v-text="$wp.trans('rejected_uri_description')"></span>
                        </label>
                        <textarea :id="$id('rejected_uri')" class="large-text ltr" v-model="settings.rejected_uri" rows="7" cols="15" :placeholder="$wp.trans('rejected_uri_placeholder')"></textarea>
                        <p class="description" v-text="$wp.trans('rejected_uri_wildcard')"></p>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 my-2">
                        <label :for="$id('rejected_user_agent')">
                            {{ $wp.trans('rejected_user_agent') }}<br>
                            <span class="font-weight-normal" v-text="$wp.trans('rejected_user_agent_description')"></span>
                        </label>
                        <textarea :id="$id('rejected_user_agent')" class="large-text ltr" v-model="settings.rejected_user_agent" rows="7" cols="15" :placeholder="$wp.trans('rejected_user_agent_placeholder')"></textarea>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 my-2">
                        <label :for="$id('rejected_cookies')">
                            {{ $wp.trans('rejected_cookies') }}<br>
                            <span class="font-weight-normal" v-text="$wp.trans('rejected_cookies_description')"></span>
                        </label>
                        <textarea :id="$id('rejected_cookies')" class="large-text ltr" v-model="settings.rejected_cookies" rows="7" cols="15" :placeholder="$wp.trans('rejected_cookies_placeholder')"></textarea>
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
        components: {Loader, TogglePanel},
        data() {
            return {
                isLoading: true,
                isSaving: false,

                settings: {
                    bypass_cache: {
                        single: null,
                        pages: null,
                        frontpage: null,
                        home: null,
                        archives: null,
                        tag: null,
                        category: null,
                        feed: null,
                        search: null,
                        author: null
                    },
                    rejected_uri: null,
                    rejected_user_agent: null,
                }
            };
        },
        async mounted() {
            this.isLoading = true;

            try {
                let response = await API.get('settings');
                this.settings = Object.assign(this.settings, response.data.data);
            } catch(error) {
                console.error(error)
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
                    this.settings.bypass_cache[field] = value;
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
