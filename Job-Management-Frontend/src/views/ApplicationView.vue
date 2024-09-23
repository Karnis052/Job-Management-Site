<template> 
<section class="bg-green-100"> 
    <div class="container m-auto max-w-2xl py-20"> 
       <div class="bg-white shadow-md rounded-lg px-4 py-6 m-6 md:m-2"> 
            <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
                <h2 class="text-center font-semibold mb-6 text-2xl">Application</h2>
                <div class="flex flex-col mb-4"> 
                    <label class="block font-semibold text-sm text-gray-700 mb-1" for="firstName"> First Name </label>
                    <input
                    v-model="form.firstName"
                    type="text"
                    id="firstName"
                    name="firstName"
                    class="border rounded-md w-full, px-3 py-1"
                    />
                </div>
                <div class="flex flex-col mb-4"> 
                    <label class="block font-semibold text-sm text-gray-700 mb-1" for="lastName"> Last Name </label>
                    <input
                    v-model="form.lastName"
                    type="text"
                    id="lastName"
                    name="lastName"
                    class="border rounded-md w-full, px-3 py-1"
                    />
                </div>

                <div class="flex flex-col mb-4"> 
                    <label class="block font-semibold text-sm text-gray-700 mb-1" for="email"> Email </label>
                    <input
                    v-model="form.email"
                    type="email"
                    id="email"
                    name="email"
                    class="border rounded-md w-full, px-3 py-1"
                    />
                </div>

                <div class="flex flex-col mb-4"> 
                    <label class="block font-semibold text-sm text-gray-700 mb-1" for="phone"> Phone </label>
                    <input
                    v-model="form.phone"
                    type="tel"
                    id="phone"
                    name="phone"
                    class="border rounded-md w-full, px-3 py-1"
                    />
                </div>

                <div class="flex flex-row  space-x-4 mb-4"> 
                    <div class="flex flex-col w-1/4"> 
                        <label class="block font-semibold text-sm text-gray-700 mb-1" for="major"> Major </label>
                        <input
                        v-model="form.major"
                        type="text"
                        id="major"
                        name="major"
                        class="border rounded-md w-full, px-3 py-1"
                        />
                    </div>

                    <div class="flex flex-col w-full"> 
                        <label class="block font-semibold text-sm text-gray-700 mb-1" for="university"> University </label>
                        <input
                        v-model="form.university"
                        type="text"
                        id="university"
                        name="university"
                        class="border rounded-md w-full, px-3 py-1"
                        />
                    </div>
                </div>

                <div class="flex flex-col mb-4"> 
                    <label class="block font-semibold text-sm text-gray-700 mb-1" for="resume"> Resume </label>
                    <input
                    @change="handleFileUpload"
                    type="file"
                    id="resume"
                    name="resume"
                    accept=".pdf"
                    class="border rounded-md w-full px-3 py-1"
                    />
                </div>
                <div class="text-center">
                    <button 
                    type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold my-6 py-2 px-4 rounded-full w-full"
                    >   
                        Submit Application
                    </button>
                </div>
            </form>
       </div>
    </div>
</section>
</template>

<script setup> 
import {ref, reactive} from 'vue';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';

const route = useRoute();
const toast = useToast();
const router = useRouter();
const postId = route.params.id;

const isLoading = ref(true);
const form = reactive({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    major: '',
    university: '',
    resume: null,
    post_id: postId,

});

const handleFileUpload = (event)=>{
    form.resume = event.target.files[0];
};

const handleSubmit = async()=>{
    isLoading.value = true;
    const formData  = new FormData();

    Object.keys(form).forEach(key =>{
        formData.append(key, form[key]);
    });

    try{
        const response = await axios.post('http://127.0.0.1:8000/api/applications/', formData, {
            headers:{
                'Content-Type': 'multipart/form-data'
            }

        });
        toast.success("Job Applied");
        router.push(`/jobs`);
    }catch(error){
        console.error('Error while creating job', error);
        toast.error(error.response?.data?.message|| "Application Failed");
    }finally{
        isLoading.value= false;
    }
}

</script>