<template>
    <div id="sidebar">
        <nav role="navigation">
            <ul role="tablist">
                <li>
                    <a
                        @click="setActive"
                        :class="activeItem === '#users' ? 'active' : null"
                        href="#users"
                        id="users-tab"
                        role="tab"
                        aria-controls="users"
                        :aria-selected="activeItem === '#users' ? 'true' : 'false'"
                    >
                        Users
                    </a>
                </li>

                <li>
                    <a
                        @click="setActive"
                        :class="activeItem === '#settings' ? 'active' : null"
                        href="#settings"
                        id="settings-tab"
                        role="tab"
                        aria-controls="settings"
                        :aria-selected="activeItem === '#settings' ? 'true' : 'false'"
                    >
                        Settings
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    export default {
        name: 'admin-sidebar',
        data() {
            return {
                activeItem: window.location.hash ? window.location.hash : '#settings'
            }
        },
        mounted() {
            window.location.hash = this.activeItem
        },
        methods: {
            setActive: function(e) {
                this.activeItem = e.target.getAttribute('href');
            }
        }
    }
</script>
