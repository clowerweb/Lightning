<template>
    <article
        :class="isActive ? 'active' : null"
        class="container"
        id="users"
        role="tabpanel"
        aria-labelledby="users-tab"
    >
        <h3 class="page-title">Users</h3>

        <table v-if="users.length" class="full">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Activated</th>
                    <th>Registered</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users">
                    <td>{{ user.email }}</td>
                    <td>
                        {{ user.is_active === '1' ? 'Yes' : null }}
                        <sel
                            v-if="user.is_active === '0'"
                            :options="[{ id: 0, name: 'No' }, { id: 1, name: 'Yes' }]"
                            :selected="user.is_active"
                            @change="changeActive(user.id, $event)"
                        ></sel>
                    </td>
                    <td>{{ user.registered_date }}</td>
                    <td>
                        <sel :options="roles" :selected="user.role" @change="changeRole(user.id, $event)"></sel>
                    </td>
                </tr>
            </tbody>
        </table>

        <p v-else>
            <strong>There are no registered users.</strong>
        </p>
    </article>
</template>

<script>
    export default {
        name: 'users-list',
        props: {
            roles: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                isActive: window.location.hash === '#users',
                users: []
            }
        },
        mounted() {
            window.addEventListener('hashchange', () => {
                this.isActive = window.location.hash === '#users';
            }, false);

            this.getUsers();
        },
        methods: {
            async getUsers() {
                await this.axios.get('/users/get-all')
                    .then((res) => {
                        if(res.data) {
                            this.users = res.data;
                        }
                    });
            },

            async changeRole(user_id, role_id) {
                const form_data = new FormData();

                form_data.append('user_id', user_id);
                form_data.append('role_id', role_id);

                await this.axios.post('/roles/update', form_data).then((res) => {
                    if(res.data.success) {
                        this.$toasted.success('User role updated', {
                            icon: 'check'
                        });
                    } else {
                        this.$toasted.error('Something went wrong, please try again', {
                            icon: 'times-octagon'
                        });
                    }
                });
            },

            async changeActive(user_id, active) {
                const form_data = new FormData();

                form_data.append('user_id', user_id);
                form_data.append('role_id', active);

                await this.axios.post('/user/activate', form_data).then((res) => {
                    if(res.data.success) {
                        this.$toasted.success('User activated', {
                            icon: 'check'
                        });
                    } else {
                        this.$toasted.error('Something went wrong, please try again', {
                            icon: 'times-octagon'
                        });
                    }
                });
            }
        }
    }
</script>

<style scoped lang="scss">
    .actions + .actions {
        margin-left: rem(5);
    }
</style>
