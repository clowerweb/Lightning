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
                    <th v-if="settings.require_activation === '1'">
                        Email Validated
                    </th>
                    <th v-if="settings.require_approval === '1'">
                        Approved
                    </th>
                    <th>Registered</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, idx) in users">

                    <td>{{ user.email }}</td>

                    <td v-if="settings.require_activation === '1'">
                        {{ user.is_active === '1' ? 'Yes' : null }}
                        <sel
                            v-if="user.is_active === '0'"
                            :options="[{ id: 0, name: 'No' }, { id: 1, name: 'Yes' }]"
                            :selected="user.is_active"
                            @change="changeActive(user.id, idx)"
                        ></sel>
                    </td>

                    <td v-if="settings.require_approval === '1'">
                        <sel
                            :options="[{ id: 0, name: 'No' }, { id: 1, name: 'Yes' }]"
                            :selected="user.is_approved === '1'"
                            @change="changeApproved(user.id, $event, idx)"
                        ></sel>
                    </td>

                    <td>{{ user.registered_date }}</td>

                    <td>
                        <sel :options="roles" :selected="user.role" @change="changeRole(user.id, $event)"></sel>
                    </td>

                    <td>
                        <a
                            v-if="user.deactivated === '0'"
                            href="#"
                            class="btn btn-error actions"
                            title="Deactivate User"
                            @click.prevent="deactivate(user.id, idx)"
                        >
                            <i class="fas fa-user-slash"></i>
                            <span class="sr">Deactivate User</span>
                        </a>
                        <a
                            v-else
                            href="#"
                            class="btn btn-success actions"
                            title="Reactivate User"
                            @click.prevent="reactivate(user.id, idx)"
                        >
                            <i class="fas fa-user-plus"></i>
                            <span class="sr">Reactivate User</span>
                        </a>
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
            },
            settings: {
                type: Object,
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

            async changeActive(user_id, user_idx) {
                const form_data = new FormData();

                form_data.append('user_id', user_id);
                console.log(user_id, user_idx);

                await this.axios.post('/user/activate', form_data).then((res) => {
                    if(res.data.success) {
                        this.users[user_idx].is_active = '1';
                        this.$toasted.success('User email validated', {
                            icon: 'check'
                        });
                    } else {
                        this.$toasted.error('Something went wrong, please try again', {
                            icon: 'times-octagon'
                        });
                    }
                });
            },

            async changeApproved(user_id, val, user_idx) {
                const form_data = new FormData();

                form_data.append('user_id', user_id);
                form_data.append('is_approved', val);

                await this.axios.post('/user/approve', form_data).then((res) => {
                    if(res.data.success) {
                        const msg = val === '0' ? 'unapproved' : 'approved';

                        this.users[user_idx].is_approved = val;
                        this.$toasted.success(`User ${msg}`, {
                            icon: 'check'
                        });
                    } else {
                        this.$toasted.error('Something went wrong, please try again', {
                            icon: 'times-octagon'
                        });
                    }
                });
            },

            async deactivate(user_id, user_idx) {
                const form_data = new FormData();

                form_data.append('user_id', user_id);

                await this.axios.post('/user/deactivate', form_data).then((res) => {
                    if(res.data.success) {
                        this.users[user_idx].deactivated = '1';
                        this.$toasted.success('User deactivated', {
                            icon: 'check'
                        });
                    } else {
                        this.$toasted.error('Something went wrong, please try again', {
                            icon: 'times-octagon'
                        });
                    }
                });
            },

            async reactivate(user_id, user_idx) {
                const form_data = new FormData();

                form_data.append('user_id', user_id);

                await this.axios.post('/user/reactivate', form_data).then((res) => {
                    if(res.data.success) {
                        this.users[user_idx].deactivated = '0';
                        this.$toasted.success('User reactivated', {
                            icon: 'check'
                        });
                    } else {
                        this.$toasted.error('Something went wrong, please try again', {
                            icon: 'times-octagon'
                        });
                    }
                });
            },
        }
    }
</script>

<style scoped lang="scss">
    .actions + .actions {
        margin-left: rem(5);
    }
</style>
