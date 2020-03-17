<template>
    <div>
        <div class="row my-3">
            <div class="col-3">
                <strong v-text="$wp.trans('license_type')"></strong>
            </div>
            <div class="col-9">
                <span v-show="!isLoading">
                    {{ license.is_trial ? $wp.trans('trial_license') : $wp.trans('regular_license') }}
                </span>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-3">
                <strong v-text="$wp.trans('license_status')"></strong>
            </div>
            <div class="col-9">
                <span v-show="!isLoading">
                    {{ license.message }}
                </span>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-3">
                <strong>{{ license.is_trial && license.is_trial_started ? $wp.trans('expires_in') : $wp.trans('expires_at') }}</strong>
            </div>
            <div class="col-9">
                <span v-show="!isLoading">
                    <span v-if="license.is_trial && license.is_trial_started" v-text="license.days_left > 0 ? $wp.trans('n_days').replace('%s', license.days_left) : $wp.trans('license_expired')"></span>
                    <span v-else-if="license.is_trial" v-text="license.days_left > 0 ? $wp.trans('trial_not_started').replace('%s', license.days_left) : $wp.trans('license_expired')"></span>
                    <span v-else-if="license.is_upress && license.licence_status === 'active'" v-text="$wp.trans('no_expiry_while_upress_client')"></span>
                    <span v-else v-text="license.licence_expire ? license.licence_expire : $wp.trans('unknown')"></span>
                </span>
            </div>
        </div>

        <div class="row my-3" v-if="!isLoading && license.uses_left >= 0">
            <div class="col-3">
                <strong v-text="$wp.trans('uses_left')"></strong>
            </div>
            <div class="col-9">
                <span v-show="!isLoading">
                    {{ license.uses_left }}
                </span>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "LicenseDetails",
        props: ['license', 'isLoading']
    }
</script>
