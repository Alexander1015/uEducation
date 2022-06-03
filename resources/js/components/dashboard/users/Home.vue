<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <!-- Snackbar -->
        <v-snackbar v-model="snackbar.active" :color="snackbar.color" :timeout="snackbar.timeout" top elevation="24">
            {{ snackbar.text }}
            <template v-slot:action="{ attrs }">
                <v-btn class="txt_white" icon v-bind="attrs" @click="snackbar.active = false">
                    <v-icon>close</v-icon>
                </v-btn>
            </template>
        </v-snackbar>
        <!-- Contenido -->
        <div class="mx-2 my-2">
            <p class="text-h5 my-4 ml-2">Usuarios / Docentes</p>
            <v-btn class="mr-10 my-2 new_btn txt_white" large color="teal" @click="newUser()">
                <v-icon class="mr-2">person_add</v-icon>
                Nuevo
            </v-btn>
            <!-- Tabla -->
        </div>
    </v-main>
</template>

<script>
export default {
    name: "HomeUser",
    data: () => ({
        overlay: false,
        snackbar: {
            active: false,
            text: "Mensaje",
            timeout: 2000,
            color: ""
        },
    }),
    methods: {
        newUser() {
            // Aqui va codigo para nuevo Usuario
        },
    },
}
</script>