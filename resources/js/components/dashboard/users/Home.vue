<template>
    <v-main>
        <v-card class="ma-auto">
            <h1>Dashboard</h1>
            <div>
                Name: {{ user.firstname + ' ' + user.lastname }} <br />
                Usuario: {{ user.user }} <br />
                Email: {{ user.email }} <br />
            </div>
        </v-card>
    </v-main>
</template>

<script>
export default {
    name: "HomeUser",
    data: () => ({
        user: {
            firstname: "",
            lastname: "",
            user: "",
            email: "",
        },
    }),
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