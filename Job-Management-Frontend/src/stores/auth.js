import { defineStore } from "pinia";
import { computed, ref } from "vue";

export const useAuthStore = defineStore('auth', ()=>{
    const token = ref(localStorage.getItem('token')); //state
    const userId = ref(localStorage.getItem('userId'));
    //getters 
    const isLoggedIn = computed(() => {
        return Boolean(token.value);
    });
    //method 
    function setAuth(newToken, newUserId) {
        token.value = newToken
        userId.value = newUserId
        localStorage.setItem('token', newToken)
        localStorage.setItem('userId', newUserId);
      }

    function clearAuth(){
        token.value = null;
        userId.value = null;
        localStorage.removeItem('token');
        localStorage.removeItem('userId');
    }

    return { token, userId, isLoggedIn, setAuth, clearAuth};
});