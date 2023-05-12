<template>
    <Title :title="themeTitle" back="true"/>
    <div class="cate-container">
        <el-row><!--         v-if="categoryType === 'mob' "-->
            <el-col class="d-flex just-center mb-2 p-1-desk pos-rel " v-for="category in categories" :key="category"
                    :xs="24" :sm="12">
                <template v-if="category.image_settings && category.image_settings.status">
                    <div class="cate-title"
                         :style="{'background-color':category.image_settings.background_color  ,'opacity': category.image_settings.opacity,}">
                        <p class="ge-medium" :style="{'color': category.image_settings.font_color  }">
                            {{ category.name }}</p>
                    </div>
                </template>
                <img @click="$router.push({name: 'Products', params: {id: category.id}})" :src="category.image"
                     class="big-cate-img cursor-pointer object-contain p-1-desk" @error="placeholderImg"/>
            </el-col>
        </el-row>
        <!--        <el-row v-else>-->
        <!--            <el-col class="d-flex just-center" v-for="category in categories" :key="img" :span="12">-->
        <!--                    <img @click="$router.push({name: 'Products', params: {id: category.id}})" :src="category.image "-->
        <!--                         class="medium-cate-img cursor-pointer" @error="placeholderImg"/>-->
        <!--            </el-col>-->
        <!--        </el-row>-->
    </div>
</template>

<script>
import Title from "../components/Title.vue";
import {useUserStore} from "../stores/user";
import {useCartStore} from "../stores/cart";

export default {
    name: "Categories",
    components: {Title},
    setup() {
        const userStore = useUserStore();
        const cartStore = useCartStore();

        return {userStore, cartStore};
    },
    async mounted() {
        let categories = await this.$http.get('/api/categories?store_id=' + this.userStore.storeId);
        let cart = await this.$http.get('/api/cart?store_id=' + this.userStore.storeId + '&user_id=' + this.userStore.userId);
        if (categories.data.length == 1) {
            this.$router.push({name: 'Products', params: {id: categories.data[0].id}})
        }
        this.categories = categories.data;
        if (this.userStore.themeId == 1) {
            this.themeTitle = 'الخدمات'
        } else {
            this.themeTitle = "الفئات"
        }

    },
    data() {
        return {
            categories: [],
            img: 'https://images.unsplash.com/photo-1664058986963-5f531744c710?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHwzfHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=500&q=60',
            imgs: ['src/assets/img/Image_2.svg', 'src/assets/img/Image_2.svg', 'src/assets/img/Image_2.svg', 'src/assets/img/Image_2.svg'],
            categoryType: 'desk',
            themeTitle: '',
        }
    },
    methods: {
        placeholderImg(event) {
            event.target.src = "/Baseet/images/PlaceholderImg.webp"
        },
    }
}
</script>

<style scoped>

</style>
