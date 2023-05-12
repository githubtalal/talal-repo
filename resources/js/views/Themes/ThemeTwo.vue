<template>
    <HeaderTwo/>
    <RouterView/>
    <Footer/>
</template>

<script>
import Footer from "../../components/Footer";
import HeaderTwo from "../../components/HeaderTwo";
export default {
    name: "ThemeTwo",
    components: {HeaderTwo, Footer, },
    props:['swapComponent'],

}
</script>

<style scoped>

</style>
