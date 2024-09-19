<script setup>
import { RouterLink, useRoute, useRouter } from 'vue-router';
import PulseLoader from 'vue-spinner/src/PulseLoader.vue';
import { reactive, onMounted, ref, computed } from 'vue';
import axios from 'axios';
import BackButton from '@/components/BackButton.vue';
import { useToast } from 'vue-toastification';
import { useAuthStore } from '@/stores/auth';

const route = useRoute();
const router = useRouter();
const toast = useToast();
const authStore = useAuthStore();

const jobId = route.params.id;
const state = reactive({
    job: {},
    isLoading: true
});

const canManageJob = computed(() => {
    return authStore.isLoggedIn && authStore.user && state.job.user_id === authStore.user.id;
});

const deleteJob = async () => {
    try {
        const confirm = window.confirm('Are you sure you want to delete this job?');
        if (confirm) {
            await axios.delete(`http://127.0.0.1:8000/api/posts/${jobId}`);
            toast.success("Job Deleted Successfully");
            router.push('/jobs')
        }
    } catch (error) {
        console.error('Error deleting job', error);
        toast.error('Job not deleted');
    }
}

onMounted(async () => {
    try {
        const response = await axios.get(`http://127.0.0.1:8000/api/posts/${jobId}`);
        state.job = response.data.data;
    } catch (error) {
        console.error('Error fetching job ', error);
    } finally {
        state.isLoading = false;
    }
});
</script>

<template>
    <div v-if="canManageJob" class="bg-white mt-6 rounded-md px-4 py-4">
        <h2 class="text-2xl font-bold mb-3">Manage Job</h2>
        <RouterLink 
            :to="`/jobs/edit/${state.job.id}`"
            class="bg-green-500 rounded-full w-full block font-bold focus:outline-none focus:shadow-outline mt-4 px-4 py-2 hover:bg-green-700 text-white text-center"
        > 
            Edit Job
        </RouterLink>
        <button   
            @click="deleteJob"
            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline mt-4 block"
        >
            Delete Job
        </button>
    </div>
</template>