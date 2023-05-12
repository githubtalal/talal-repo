<template>
    <nav class="">
        <div class="el-container container">
            <el-row class="w-full">
                <el-col :sm="12" :xs="12">
                    <a href="https://ecart.sy/" target="_blank">
                        <img class="img-size" src="../assets/img/EcartLogo.svg"/>
                    </a>
                </el-col>
                <el-col :sm="12" :xs="12" class="d-flex just-end">
                    <img class="img-size" @error="placeholderImg" :src="userStore.logo || placeholderImg" />
                </el-col>
            </el-row>
        </div>
    </nav>
</template>

<script>
import {useUserStore} from "../stores/user";

export default {
    name: 'Header',
    setup() {
        const userStore = useUserStore();
        return {userStore}
    },
    methods:{
        placeholderImg(event){
            event.target.src = "/Baseet/images/PlaceholderImg.webp"
        }
    }

}
</script>

<style scoped>
.flex-grow {
    flex-grow: 1;
}
</style>
