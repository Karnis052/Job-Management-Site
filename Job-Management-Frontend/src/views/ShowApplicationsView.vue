<template>
    <section>
        <h1 class="text-2xl font-bold mb-4">Applications for Post</h1>
        <div v-if="state.isLoading" class="text-center">
            <p>Loading applications...</p>
        </div>
        <div v-else-if="state.error" class="text-red-500">
            {{ state.error }}
        </div>
        <div v-else-if="state.applications.length === 0" class="text-gray-500">
            No applications found for this post.
        </div>
        <ul v-else class="space-y-4">
            <li v-for="application in state.applications" :key="application.id" class="border p-4 rounded-lg">
            <h2 class="font-semibold">{{ application.firstName }}</h2>
            <p>{{ application.lastName }}</p>
            </li>
        </ul>
    </section>
</template>

<script setup>
import axios from 'axios';
import { reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const postId = route.params.id;

const state = reactive({
    applications: [],
    isLoading:true,
    error: null,
})

onMounted(async()=>{
    try{
        const response = await axios.get(`http://127.0.0.1:8000/api/posts/${postId}/applications`);
        state.applications = response.data.data.data;
        console.log(state.applications);
    }catch(error){
        console.error('Error fetching applications:', error);
        state.error = 'Failed to load applications. Please try again later.';
    }finally{
        state.isLoading = false;
    }
});

</script>
