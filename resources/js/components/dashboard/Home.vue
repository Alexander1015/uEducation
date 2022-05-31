<template>
    <v-main>
        <v-card class="ma-auto">
            <h1>Dashboard</h1>
            <div>
                Name: {{ user.firstname + ' ' + user.lastname }} <br />
                Email: {{ user.email }} <br />
                <v-btn class="mt-4 bk_brown txt_white btn_login" @click.prevent="logout">Salir de la sesión</v-btn>
            </div>
        </v-card>
    </v-main>
</template>

<script>
export default {
    name: "HomeDashboard",
    data: () => ({
        user: {
            firstname: "",
            lastname: "",
            email: "",
            user: "",
        },
    }),
    methods: {
        logout() {
            this.axios.post('/api/logout')
                .then(response => {
                    this.user.firstname = "";
                    this.user.lastname = "";
                    this.user.email = "";
                    this.user.user = "";
                    this.$router.push({ name: "auth" });
                }).catch((error) => {
                    console.log(error);
                });
        },
    },
    mounted() {
        this.axios.get('/api/auth')
            .then(response => {
                this.user = response.data;
            }).catch((error) => {
                console.log(error);
            });
    }
}
</script>