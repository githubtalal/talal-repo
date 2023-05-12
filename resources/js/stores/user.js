import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
    state: () => {
        return {
            logo: '',
        }
    },
    getters: {
        csrfToken: (state) => document.querySelector('body').dataset.csrfToken,
        userId: (state) => document.querySelector('body').dataset.userId,
        storeId: (state) => document.querySelector('body').dataset.storeId,
        themeId: (state) => document.querySelector('body').dataset.theme,
        storeInfo: (state) => JSON.parse(document.querySelector('body').dataset.storeInfo),
    },

})
