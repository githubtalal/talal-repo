<template>
    <nav class="">
        <div class="el-container container">
            <el-row class="w-full justify-content-center">
                <el-col :sm="12" :xs="12" align="center">
                    <a href="https://ecart.sy/" target="_blank">
                        <img class="img-size" src="./assets/img/EcartLogo.svg"/>
                    </a>
                </el-col>
            </el-row>
        </div>
    </nav>

    <Title title="الجمعيات" back="false" class="cursor-pointer"/>

    <div class="cate-container">
        <el-row>

            <el-col class="d-flex just-center mb-2 p-1-desk pos-rel " v-for="charity in charities" :xs="24" :sm="12">
                <img @click="open(charity)"
                     :src="charity.image"
                     class="big-cate-img cursor-pointer object-contain p-1-desk"/>
            </el-col>

        </el-row>
    </div>
</template>
<script setup>
import Title from "./components/Title.vue";
</script>

<script>
import axios from "axios";

export default {
    data() {
        return {
            charities: [],
        }
    },
    async mounted() {
        const self = this;
        let id = this.generateId();
        fetch(`https://ecart.sy/charity/charities.json?id=${id}`)
            .then(response => response.json())
            .then(response => self.charities = response);
    },
    methods: {
        open(charity) {
            window.open(charity.url, '_blank');
        },
        generateId(length = 8) {
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            const charactersLength = characters.length;
            let counter = 0;
            while (counter < length) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
                counter += 1;
            }
            return result;
        }

    }
}
</script>
