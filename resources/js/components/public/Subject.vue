<template>
    <v-main>
        <!-- Overlay -->
        <v-overlay :value="overlay">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-navigation-drawer id="nav_subject" v-model="nav" dense flat permanent expand-on-hover>
            <v-list class="pa-0 mx-auto" nav dense>
                <v-list-item>
                    <v-list-item-avatar>
                        <v-img :src="subject.img" :lazy-src='subject.lazy_img'>
                            <template v-slot:placeholder>
                                <v-row class="fill-height ma-0" align="center" justify="center">
                                    <v-progress-circular indeterminate color="grey lighten-5">
                                    </v-progress-circular>
                                </v-row>
                            </template>
                        </v-img>
                    </v-list-item-avatar>
                    <v-list-item-title class="white_space caption text-uppercase font-weight-black">
                        {{ subject.name }}
                    </v-list-item-title>
                </v-list-item>
            </v-list>
            <v-divider></v-divider>
            <v-list nav dense>
                <v-list-item link @click.stop="returnContent()">
                    <v-list-item-icon>
                        <v-icon>keyboard_double_arrow_left</v-icon>
                    </v-list-item-icon>
                    <v-list-item-title class="caption font-weight-bold">
                        Regresar
                    </v-list-item-title>
                </v-list-item>
            </v-list>
            <v-divider></v-divider>
            <v-list nav dense>
                <template v-for="item in topics">
                    <v-list-item link :to='{ name: "publicTopic", params: { topic: item.slug } }'
                        @click.stop="overlay = true">
                        <v-list-item-icon>
                            <v-avatar size="25" class="caption bk_tags txt_white">
                                {{ item.key }}
                            </v-avatar>
                        </v-list-item-icon>
                        <v-list-item-title class="white_space caption font-weight-bold">
                            {{ item.name }}
                        </v-list-item-title>
                    </v-list-item>
                </template>
            </v-list>
        </v-navigation-drawer>
        <!-- Contenido -->
        <v-container id="data_container" fluid class="my-1">
            <router-view></router-view>
        </v-container>
    </v-main>
</template>

<script>
export default {
    name: 'PublicSubject',
    data: () => ({
        subject: {
            name: "",
            img: "",
            lazy_img: "",
        },
        nav: false,
        topics: {},
        overlay: false,
    }),
    mounted() {
        this.getSubject();
    },
    computed: {
        address() {
            return this.$route.params.topic;
        }
    },
    watch: {
        address() {
            this.getSubject();
        }
    },
    methods: {
        returnContent() {
            this.overlay = true;
            this.$router.push({ name: "contents" });
        },
        async getSubject() {
            this.overlay = true;
            if (this.$route.params.subject) {
                await this.axios.get('/api/getdata/' + this.$route.params.subject)
                    .then(response => {
                        let item = response.data;
                        if (!item.subject.name) {
                            this.$router.push({ name: "error" });
                        }
                        else {
                            this.subject.name = item.subject.name;
                            this.topics = item.topics;
                            if (item.subject.img) {
                                this.subject.img = "/img/subjects/" + item.subject.img;
                                this.subject.lazy_img = "/img/lazy_subjects/" + item.subject.img;
                            }
                            else {
                                this.subject.img = "/img/subjects/blank.png";
                                this.subject.lazy_img = "/img/lazy_subjects/blank.png";
                            }
                            this.overlay = false;
                        }
                        if (!this.$route.params.topic && item.topics.length > 0) {
                            this.$router.push({ name: "publicTopic", params: { topic: item.topics[0].slug } });
                        }
                        else if (item.topics.length <= 0) {
                            this.$router.push({ name: "error" });
                        }
                    }).catch((error) => {
                        console.log(error);
                        this.$router.push({ name: "error" });
                    });
            }
            else {
                this.$router.push({ name: "error" });
            }
        },
    },
}
</script>