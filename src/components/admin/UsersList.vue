<template>
    <div
        :class="isActive ? 'active' : null"
        class="container"
        id="users"
        role="tabpanel"
        aria-labelledby="users-tab"
    >
        <h3 class="page-title">Users</h3>
    </div>
</template>

<script>
    export default {
        name: 'users-list',
        props: ['users'],
        data() {
            return {
                isActive: window.location.hash === '#users'
            }
        },
        mounted() {
            window.addEventListener('hashchange', () => {
                this.isActive = window.location.hash === '#users';
            }, false);
        }
    }
</script>
