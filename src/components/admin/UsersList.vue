<template>
    <div
        :class="isActive ? 'active' : null"
        class="container"
        id="users"
        role="tabpanel"
        aria-labelledby="users-tab"
    >
        <h3 class="page-title">Users</h3>

        <table v-if="users.length">
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
                    <td>{{ user.is_active === '1' ? 'Yes' : 'No' }}</td>
                    <td>{{ user.registered_date }}</td>
                    <td>{{ user.role }}</td>
                </tr>
            </tbody>
        </table>

        <p v-else><strong>There are no registered users.</strong></p>
    </div>
</template>

<script>
    export default {
        name: 'users-list',
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

            this.axios.get('/users/get-all')
                .then((res) => {
                    if(res.data) {
                        this.users = res.data;
                    }
                });
        }
    }
</script>
