<template>
    <el-row class="w-full pb-half back-grey desk">
        <el-col class="desk d-flex just-center align-center" :sm="24" :xs="12">
            <img class="mins-logo" :src="userStore.storeInfo.logo"/>
        </el-col>
        <el-col :sm="24" :xs="12">
            <el-row class="w-full">
                <el-col class="d-flex just-center align-center" :sm="24" :xs="12">
                    <p class="text-grey-2 ge-medium m-0" style="font-size: 24px">
                        {{ userStore.storeInfo.name}}
                    </p>
                </el-col>
                <el-col v-if="userStore.storeInfo.subname" class="d-flex just-center align-center" :sm="24" :xs="12">
                    <p class="text-grey-2 ge-medium m-0 " style="font-size: 18px">
                        {{ userStore.storeInfo.subname }}
                    </p>
                </el-col>
            </el-row>
        </el-col>
    </el-row>
    <el-row class="w-full pb-half back-grey d-flex-mob just-center align-center">
        <el-col class="desk d-flex just-center align-center" :sm="12" :xs="12">
            <img class="mins-logo" :src="userStore.storeInfo.logo"/>
        </el-col>
    </el-row>
    <el-row class="w-full pb-half back-grey d-flex-mob just-center align-center">
            <el-col :sm="12" :xs="12">
                <el-row class="w-full" style="margin-top: 0.5em;">
                    <el-col class="d-flex just-center align-center" :sm="12" :xs="24">
                        <p class="text-grey-2 ge-medium f-14 m-0" style="font-size: 20px">
                            {{ userStore.storeInfo.name}}
                        </p>
                    </el-col>
                    <el-col v-if="userStore.storeInfo.subname" class="d-flex just-center align-center" :sm="12" :xs="24">
                        <p class="text-grey-2 ge-medium f-14 m-0" style="font-size: 20px">
                            {{ userStore.storeInfo.subname }}
                        </p>
                    </el-col>

                </el-row>
            </el-col>
    </el-row>

</template>

<script>
import {useUserStore} from '../stores/user';

export default {
    setup() {
        const userStore = useUserStore();

        return {userStore}
    },
    name: "HeaderTwo"
}
</script>

<style scoped>

</style>
