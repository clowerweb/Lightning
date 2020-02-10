<template>
    <div :class="isActive ? 'active' : null"
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
                <label for="timezone">Default Timezone:</label>
                <input type="text" id="timezone" name="default_timezone" :value="settings.default_timezone"/>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'settings-form',
        props: ['settings'],
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
