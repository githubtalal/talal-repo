<template>
    <footer>
            <el-row class="w-full back-grey">
                <el-col class="d-flex just-end align-center" :sm="12" :xs="12">
                    <p class="text-grey work-sans footer-text">Powered by</p>
                </el-col>
                <el-col class="d-flex align-center" :sm="12" :xs="12">
                    <a href="https://ecart.sy/" target="_blank">
                        <img class="footer-logo" src="../assets/img/EcartLogo.svg"/>
                    </a>
                </el-col>
            </el-row>
    </footer>
</template>

<script>
export default {
    name: "Footer"
}
</script>

<style scoped>
    footer {

        bottom: 0;
        left: 0;
        right: 0;
        position: fixed;
        width: 100%;
        margin: 0 auto;
        max-width: 800px;
    }
</style>
