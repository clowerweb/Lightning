<template>
    <article
        :class="isActive ? 'active' : null"
        class="container"
        id="settings"
        role="tabpanel"
        aria-labelledby="settings-tab"
    >
        <h3 class="page-title">Site settings</h3>

        <form method="post" action="/admin/save-settings">
            <div>
                <label for="site-name">Site Name:</label>
                <input type="text" id="site-name" name="site_name" :value="settings.site_name" required/>
            </div>

            <div>
                <label for="site-tag">Site Tagline:</label>
                <input type="text" id="site-tag" name="site_tagline" :value="settings.site_tagline"/>
            </div>

            <div>
                <label>
                    Allow User Registration?
                    <input type="checkbox" name="allow_registration" :checked="settings.allow_registration === '1'"/>
                </label>
            </div>

            <div>
                <label>
                    Require Email Activation?
                    <input type="checkbox" name="require_activation" :checked="settings.require_activation === '1'"/>
                </label>
            </div>

            <div>
                <label>
                    Require Admin Approval for New Users?
                    <input type="checkbox" name="require_approval" :checked="settings.require_approval === '1'"/>
                </label>
            </div>

            <div>
                <label for="timezone">Default Timezone:</label>
                <sel :name="'default_timezone'" :options="timezones" :selected="settings.default_timezone"></sel>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </article>
</template>

<script>
    export default {
        name: 'settings-form',
        props: {
            settings: {
                type: Object,
                required: true
            },
            timezones: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                isActive: window.location.hash === '#settings'
            }
        },
        mounted() {
            window.addEventListener('hashchange', () => {
                this.isActive = window.location.hash === '#settings';
            }, false);
        }
    };
</script>
