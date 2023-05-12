<template>
    <el-button class="warning-btn" @click="open">
         <img  src=../assets/img/DeleteIcon.svg class= cursor-pointer/>
    </el-button>
</template>

<script>
import {ElMessageBox} from "element-plus";
import {useCartStore} from "../stores/cart";
import {useUserStore} from "../stores/user";

export default {
    name: "WarningModal",
    setup() {
        const cartStore = useCartStore();
        const userStore = useUserStore();
        return {cartStore, userStore};
    },
    props:[
        'modalTitle',
        'modalBody',
        'product'
    ],
    methods:{
        open() {
            ElMessageBox.confirm(
                this.$props.modalBody + this.product.product.name + '؟',
                this.$props.modalTitle,
                {
                    confirmButtonText: 'موافق',
                    cancelButtonText: 'إلغاء',
                    type: 'warning',
                }
            )
                .then(() => {
                this.removeFromCart(this.product)
                    ElMessage({
                        type: 'success',
                        message: 'Delete completed',
                    })
                })
                .catch(() => {
                    ElMessage({
                        type: 'info',
                        message: 'Delete canceled',
                    })
                })
        },
        async removeFromCart(cartItem) {
            document.getElementById('loader').classList.remove('d-none');
            if (!cartItem) return;
            let response = await this.$http.get(`/api/removeFromCart/${cartItem.id}`, {
                params: {
                    'user_id': this.userStore.userId,
                    'store_id': this.userStore.storeId,
                }
            });
            if (response.data.success) {
                document.getElementById('loader').classList.add('d-none');
                this.cartStore.initCart(response.data);
                if (this.cartStore.itemsCount == 0){
                    this.$router.push({name:'Categories'})
                }

            }else{
                ElMessageBox({
                    title: 'خطأ',
                    message: " حدث خطأ ما الرجاء إعادة المحاولة لاحقا",
                    showCancelButton: 'العودة',
                    confirmButtonText: false,
                    type: 'error',
                })
            }
        },
    }
}
</script>

<style scoped>
.el-message-box__btns{
    justify-content: start !important;
}
</style>
