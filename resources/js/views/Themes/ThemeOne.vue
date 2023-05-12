<template>
    <Header/>
    <RouterView/>
</template>

<script>
import Header from "../../components/Header";
export default {
    name: "ThemeOne",
    components: {Header},
    props:['swapComponent'],
}
</script>

<style scoped>

</style>
