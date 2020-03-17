<template>
    <fragment>
        <header class="ezcache-header">
            <h1>
                <svg class="logo" viewBox="0 0 360 68" version="1.1" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" fill-rule="evenodd" clip-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2">
                    <g fill-rule="nonzero">
                        <path d="M51.154 53.752H13.691V37.239h22.056l9.144-12.855h-31.2V13.378H52.72L62.235 0H0v67.338h45.91l5.244-13.586z" fill="#1e1e1e"/>
                        <path d="M72.596 24.521h21.219L53.38 67.338h58.228V54.797H80.976l39.09-41.42H80.389l-7.794 11.144z" fill="#1e1e1e"/>
                        <path d="M57.073 13.378L40.101 37.239h21.603l-12.568 30.1 37.678-39.923H66.051l9.985-14.038H57.073z" fill="#fc0"/>
                        <path d="M57.074 13.378L40.102 37.239h21.602L49.136 67.338l23.038-35.036H52.435l14.12-18.924h-9.481z" fill="url(#_Linear1)"/>
                        <path d="M200.202 47.707h-19.221l9.611-22.993 9.61 22.993zm8.279 19.631h10.645l-23.212-53.965h-10.645l-23.213 53.965h10.572l4.435-10.163h27.056l4.362 10.163z" fill="gray"/>
                        <path d="M313.541 67.338V13.373h-9.684V36.29h-25.43V13.373h-9.758v53.965h9.758v-22.03h25.43v22.03h9.684z" fill="gray"/>
                        <path d="M360 57.728h-29.053V44.865h16.255l3.911-9.092h-20.166V22.835h24.983l4.07-9.461h-38.736v53.964H360v-9.61z" fill="gray"/>
                        <path d="M143.812 13.373h15.746v9.314h-15.746c-12.493 0-17.89 9.315-17.815 17.964.073 8.575 5.026 17.52 17.815 17.52h15.659l-3.943 9.167H143.812c-19.22 0-27.352-13.233-27.426-26.687-.073-13.528 8.723-27.278 27.426-27.278z" fill="gray"/>
                        <path d="M245.201 13.373h15.746v9.314H245.201c-12.493 0-17.89 9.315-17.816 17.964.074 8.575 5.027 17.52 17.816 17.52h15.746v9.167H245.201c-19.221 0-27.352-13.233-27.426-26.687-.074-13.528 8.723-27.278 27.426-27.278" fill="gray"/>
                    </g>
                    <defs>
                        <linearGradient id="_Linear1" x1="0" y1="0" x2="1" y2="0" gradientUnits="userSpaceOnUse" gradientTransform="matrix(32.072 0 0 -32.072 40.101 40.358)">
                            <stop offset="0" stop-color="#deb203" stop-opacity="1"/>
                            <stop offset="1" stop-color="#fc0" stop-opacity="1"/>
                        </linearGradient>
                    </defs>
                </svg>
                <span class="screen-reader-text">
                    {{ $wp.trans('ezcache') }}
                </span>
            </h1>
        </header>

        <div class="row">
            <div class="col-12 order-first col-sm-4 col-md-3 col-xl-2">
                <nav class="ezcache-nav">
                    <router-link to="/" exact activeClass="nav-item-active" class="nav-item">
                        {{ $wp.trans('stats') }}
                    </router-link>
                    <router-link to="/settings" activeClass="nav-item-active" class="nav-item">
                        {{ $wp.trans('settings') }}
                    </router-link>
                    <router-link to="/advanced" activeClass="nav-item-active" class="nav-item">
                        {{ $wp.trans('advanced_settings') }}
                    </router-link>
                    <router-link to="/license" activeClass="nav-item-active" class="nav-item">
                        {{ $wp.trans('license') }}
                    </router-link>
                </nav>
            </div>

            <div class="col-12 col-sm-4 order-sm-last col-md-3 col-xl-2 mb-4">
                <div class="ezcache-nav mt-1">
                    <AjaxButton class="wpb-button" endpoint="cache" method="delete" @success="$eventBus.$emit('wpb.cache_cleared'); toast($wp.trans('cache_cleared'), 'success')">
                        <svg viewBox="0 0 24 24">
                            <path d="M19,8L15,12H18A6,6 0 0,1 12,18C11,18 10.03,17.75 9.2,17.3L7.74,18.76C8.97,19.54 10.43,20 12,20A8,8 0 0,0 20,12H23M6,12A6,6 0 0,1 12,6C13,6 13.97,6.25 14.8,6.7L16.26,5.24C15.03,4.46 13.57,4 12,4A8,8 0 0,0 4,12H1L5,16L9,12" />
                        </svg>
                        {{ $wp.trans('clear_cache') }}
                    </AjaxButton>

                    <a :href="$wp.trans('speed_test_url').replace('%s', encodeURIComponent($wp.site_url))" class="mt-2 wpb-button wpb-button-outlined" target="_blank" rel="nofollow noopener">
                        <svg viewBox="0 0 24 24">
                            <path d="M12,16A3,3 0 0,1 9,13C9,11.88 9.61,10.9 10.5,10.39L20.21,4.77L14.68,14.35C14.18,15.33 13.17,16 12,16M12,3C13.81,3 15.5,3.5 16.97,4.32L14.87,5.53C14,5.19 13,5 12,5A8,8 0 0,0 4,13C4,15.21 4.89,17.21 6.34,18.65H6.35C6.74,19.04 6.74,19.67 6.35,20.06C5.96,20.45 5.32,20.45 4.93,20.07V20.07C3.12,18.26 2,15.76 2,13A10,10 0 0,1 12,3M22,13C22,15.76 20.88,18.26 19.07,20.07V20.07C18.68,20.45 18.05,20.45 17.66,20.06C17.27,19.67 17.27,19.04 17.66,18.65V18.65C19.11,17.2 20,15.21 20,13C20,12 19.81,11 19.46,10.1L20.67,8C21.5,9.5 22,11.18 22,13Z" />
                        </svg>
                        {{ $wp.trans('run_speed_test') }}
                    </a>
                </div>

                <div class="side-links">
                    <a class="side-links-item" :href="$wp.trans('ezcache_docs_link')" v-text="$wp.trans('documentation')" target="_blank" rel="nofollow noopener"></a>
                    <a class="side-links-item" :href="$wp.trans('ezcache_knowledgebase_link')" v-text="$wp.trans('knowledgebase')" target="_blank" rel="nofollow noopener"></a>

                    <a class="side-links-item mt-4 pt-2" :href="$wp.trans('upress_ad_link')" target="_blank" rel="nofollow noopener">
                        <UpressAdvert aria-hidden="true"></UpressAdvert>
                        <span class="screen-reader-text" v-text="$wp.trans('upress_ad')"></span>
                    </a>
                </div>
            </div>

            <div class="col-12 col-sm-4 order-sm-first col-md-6 col-xl-8">
                <section class="ezcache-main">
                    <transition name="slide-fade" mode="out-in">
                        <router-view></router-view>
                    </transition>
                </section>
            </div>
        </div>
    </fragment>
</template>
<script>
    import AjaxButton from "../Components/AjaxButton";
    import UpressAdvert from '../Components/UpressAdvert';

    export default {
        components: {UpressAdvert, AjaxButton}
    };
</script>
