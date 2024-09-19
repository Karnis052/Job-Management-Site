<template>
  <nav class="bg-green-700 border border-green-500 p-4">
    <div class="container mx-auto flex flex-wrap justify-between items-center">
      
        <div class="flex items-center"> 
            <RouterLink to ="/" class="flex flex-shrink-0 items-center mr-4">
              <img class="h-8 w-auto" :src="logo" alt="Vue Jobs"/>
              <span class="hidden md:block text-white font-semibold text-2xl ml-2">Vue Jobs</span>
            </RouterLink>
        </div>

        <div class="flex items-center sm:mt-0"> 
            <div class="text-white space-x-2 flex flex-wrap"> 
                <RouterLink to="/"  :class= "getLinkClass('/')">
                    Home
                </RouterLink>
                <RouterLink to="/jobs"  :class="getLinkClass('/jobs')">
                    Jobs
                </RouterLink>
                <RouterLink v-if="authStore.isLoggedIn" to="/jobs/add"  :class="getLinkClass('/jobs/add')" >
                    Add Job
                </RouterLink>

              <template v-if="!authStore.isLoggedIn">
                <RouterLink to="/signup" :class="getLinkClass('/signup')" class="text-sm">
                  Sign Up
                </RouterLink>
                <RouterLink to="/login" :class="getLinkClass('/login')" class="text-sm"> 
                  Log In
                </RouterLink>
              </template>
              <button v-else @click="logout" class="hover:bg-gray-900 px-3 py-2 rounded-md text-sm">
                Logout
              </button>
            </div>

        </div>
    </div>
  </nav>
</template>

<script setup>
import logo from '@/assets/img/logo.png'
import { RouterLink, useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();


const isActiveLink = (routePath)=>{
   return route.path === routePath;
}

const getLinkClass = (routePath)=>{
  return [
    isActiveLink(routePath)? 'bg-green-900':'hover:bg-gray-900',
    'px-3', 'py-2', 'rounded-md',  'whitespace-nowrap'
  ];
}

const logout = ()=>{
  authStore.clearAuth();
  router.push('/');
}

</script>