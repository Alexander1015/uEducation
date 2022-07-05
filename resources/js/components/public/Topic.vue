<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <div class="ml-16">
            <v-card class="mt-2 mx-auto" elevation="2" max-width="1200">
                <template v-if="overlay == false">
                    <!-- Imagen -->
                    <v-img class="mx-auto" :src='topic.img' :lazy-src='topic.lazy_img' max-height="250">
                        <template v-slot:placeholder>
                            <v-row class="fill-height ma-0" align="center" justify="center">
                                <v-progress-circular indeterminate color="grey lighten-5">
                                </v-progress-circular>
                            </v-row>
                        </template>
                    </v-img>
                    <!-- Tags -->
                    <template v-if="topic.tags.length > 0">
                        <div class="mt-6 mx-4">
                            <template v-for="item in topic.tags">
                                <v-chip label :color="item.background_color" :style='"color:" + item.text_color + ";"'>
                                    <v-icon left>label</v-icon> {{ item.name }}
                                </v-chip>
                            </template>
                        </div>
                    </template>
                    <!-- Titulo -->
                    <v-card-title>
                        <span class="text-h5 text-uppercase font-weight-bold">
                            {{ topic.name }}
                        </span>
                    </v-card-title>
                    <!-- Usuario -->
                    <v-card-text>
                        <v-list-item>
                            <v-list-item-avatar>
                                <v-img :src="topic.user_update ? topic.user_avatar_update : topic.user_avatar"
                                    max-height="38" max-width="38"
                                    :lazy-src="topic.user_update ? topic.user_lazy_avatar_update : topic.user_lazy_avatar">
                                    <template v-slot:placeholder>
                                        <v-row class="fill-height ma-0" align="center" justify="center">
                                            <v-progress-circular indeterminate color="grey lighten-5">
                                            </v-progress-circular>
                                        </v-row>
                                    </template>
                                </v-img>
                            </v-list-item-avatar>
                            <v-list-item-title>
                                <span class="subtitle-1 font-italic font-weight-bold">
                                    {{ topic.user_update ? topic.user_update : topic.user }}
                                </span>
                                <br />
                                <span class="subtitle-2 font-italic">
                                    Contacto: {{ topic.user_update ? topic.user_update_email : topic.user_email }}
                                </span>
                                <br />
                                <span class="subtitle-2 font-italic">
                                    Ultima actualización: {{ topic.user_update ? topic.updated_at : topic.created_at }}
                                </span>
                            </v-list-item-title>
                        </v-list-item>
                    </v-card-text>
                    <!-- Abstract -->
                    <template v-if="topic.abstract">
                        <v-card-text>
                            <div id="data_abstract" class="bk_tags_bk txt_black mx-2 pa-2">
                                <span class="subtitle-2 font-weight-bold">
                                    Descripción
                                </span>
                                <br />
                                <span class="body-2">
                                    {{ topic.abstract }}
                                </span>
                            </div>
                        </v-card-text>
                    </template>
                    <!-- Body -->
                    <v-card-text>
                        <div class="px-2" id="data_body">
                            {{ topic.body }}
                        </div>
                    </v-card-text>
                </template>
                <template v-else>
                    <v-row>
                        <v-col cols="12">
                            <v-skeleton-loader v-bind="attrs" type="list-item-avatar-three-line, image, article">
                            </v-skeleton-loader>
                        </v-col>
                    </v-row>
                </template>
            </v-card>
        </div>
    </v-main>
</template>

<script>
export default {
    name: 'PublicTopic',
    data: () => ({
        overlay: false,
        topic: {
            name: "",
            abstract: "",
            body: "",
            created_at: "",
            updated_at: "",
            img: "",
            lazy_img: "",
            user: "",
            user_email: "",
            user_avatar: "",
            user_lazy_avatar: "",
            user_update: "",
            user_update_email: "",
            user_avatar_update: "",
            user_lazy_avatar_update: "",
            subject: "",
            tags: [],
        },
    }),
    mounted() {
        this.getTopic();
    },
    methods: {
        async getTopic() {
            this.overlay = true;
            if (this.$route.params.subject && this.$route.params.topic) {
                await this.axios.get('/api/gettopic/' + this.$route.params.topic)
                    .then(response => {
                        const item = response.data;
                        if (!item.topic) {
                            this.overlay = false;
                            this.$router.push({ name: "publicSubject", params: { subject: this.$route.params.subject } });
                        }
                        else {
                            this.topic.name = item.topic.name;
                            this.topic.abstract = item.topic.abstract;
                            this.topic.user = item.topic.user;
                            this.topic.user_email = item.topic.user_email;
                            this.topic.user_update = item.topic.user_update;
                            this.topic.user_update_email = item.topic.user_update_email;
                            this.topic.subject = item.topic.subject;
                            this.topic.created_at = item.topic.created_at;
                            this.topic.updated_at = item.topic.updated_at;
                            this.topic.body = item.topic.body;
                            // Imagen del topic
                            if (item.topic.img) {
                                this.topic.img = "/img/topics/" + item.topic.img;
                                this.topic.lazy_img = "/img/lazy_topics/" + item.topic.img;
                            }
                            else {
                                this.topic.img = "/img/topics/blank.png";
                                this.topic.lazy_img = "/img/lazy_topics/blank.png";
                            }
                            // Imagenes de los usuarios
                            if (item.topic.user_avatar) {
                                this.topic.user_avatar = "/img/users/" + item.topic.user_avatar;
                                this.topic.user_lazy_avatar = "/img/lazy_users/" + item.topic.user_avatar;
                            }
                            else {
                                this.topic.user_avatar = "/img/users/blank.png";
                                this.topic.user_lazy_avatar = "/img/lazy_users/blank.png";
                            }
                            // Imagenes de los usuarios
                            if (item.topic.user_update_avatar) {
                                this.topic.user_avatar_update = "/img/users/" + item.topic.user_update_avatar;
                                this.topic.user_lazy_avatar_update = "/img/lazy_users/" + item.topic.user_update_avatar;
                            }
                            else {
                                this.topic.user_avatar_update = "/img/users/blank.png";
                                this.topic.user_lazy_avatar_update = "/img/lazy_users/blank.png";
                            }
                            // Tags
                            if (item.tags) {
                                this.topic.tags = item.tags;
                                console.log(this.topic.tags)
                            }
                            this.overlay = false;
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.overlay = false;
                        this.$router.push("/");
                    });
            }
            else if (this.$route.params.subject && !this.$route.params.topic) {
                this.overlay = false;
                this.$router.push({ name: "publicSubject", params: { subject: this.$route.params.subject } });
            }
            else {
                this.overlay = false;
                this.$router.push("/");
            }
        }
    },
}
</script>