<template> 
    <section class="bg-green-100">
      <div class="container m-auto max-w-2xl py-20">
            <div class="bg-white shadow-md rounded-lg px-6 py-6 m-6 md:m-2"> 
                <form @submit.prevent="handleSubmit"> 
                    <h2 class="text-center font-semibold mb-6 text-3xl">Edit Job</h2>

                    <div class="flex flex-col mb-6"> 
                        <label for="type" class="block font-semibold text-gray-700 mb-2">
                            Job Type
                        </label>
                        <select 
                            v-model="form.type"
                            id = 'type'
                            name = 'type'
                            class="border w-full rounded px-3 py-2"
                            required
                        >
                            <option value="Full-Time">Full-Time</option>
                            <option value="Part-Time">Part-Time</option>
                            <option value="Remote">Remote</option>
                            <option value="Internship">Internship</option>
                        </select>
                    </div>

                    <div class="flex flex-col mb-6"> 
                        <label for="name" class="block font-semibold text-gray-700 mb-2">
                            Job Lisiting Name
                        </label>
                        <input
                            v-model="form.title"
                            type="text"
                            id="name"
                            name="name"
                            class="border w-full rounded px-3 py-2"
                            placeholder="eg. Beautiful Workplace"
                            required
                        />
                    </div>

                    <div class="flex flex-col mb-6"> 
                        <label for="description" class="block text-gray-700 font-bold mb-2"> 
                            Description
                        </label>
                        <textarea
                            v-model="form.description"
                            id="description"
                            name="description"
                            class="border rounded px-3 py-2 w-full"
                            rows="4"
                            placeholder="Add any job duties, expectations, requirements, etc"
                        > 
                        </textarea>
                    </div>

                    <div class="flex flex-col mb-6"> 
                        <label for="salary" class="block font-semibold text-gray-700 mb-2">
                            Salary
                        </label>
                        <select
                            v-model="form.salary"
                            id="salary"
                            name="salary"
                            class="border rounded w-full py-2 px-3"
                            required
                        >
                            <option value="Under $50K">under $50K</option>
                            <option value="$50K - $60K">$50 - $60K</option>
                            <option value="$60K - $70K">$60 - $70K</option>
                            <option value="$70K - $80K">$70 - $80K</option>
                            <option value="$80K - $90K">$80 - $90K</option>
                            <option value="$90K - $100K">$90 - $100K</option>
                            <option value="$100K - $125K">$100 - $125K</option>
                            <option value="$125K - $150K">$125 - $150K</option>
                            <option value="$150K - $175K">$150 - $175K</option>
                            <option value="$175K - $200K">$175 - $200K</option>
                            <option value="Over $200K">Over $200K</option>
                        </select>
                    </div>
                    <div class="mb-4">
                    <label for="location" class="block text-gray-700 font-bold mb-2">
                        Location
                    </label>
                    <input
                        v-model="form.location"
                        type="text"
                        id="location"
                        name="location"
                        class="border rounded w-full py-2 px-3 mb-2"
                        placeholder="Company Location"
                        required
                    />
                    </div>

                    <h3 class="text-2xl mb-5">Company Info</h3>
                    <div class="mb-4">
                    <label for="company" class="block text-gray-700 font-bold mb-2"
                        >Company Name</label
                    >
                    <input
                        v-model="form.company_name"
                        type="text"
                        id="company"
                        name="company"
                        class="border rounded w-full py-2 px-3"
                        placeholder="Company Name"
                    />
                    </div>
                    <div class="mb-4">
                    <label
                       
                        for="company_description"
                        class="block text-gray-700 font-bold mb-2"
                        >Company Description</label
                    >
                    <textarea
                        v-model="form.company_description"
                        id="company_description"
                        name="company_description"
                        class="border rounded w-full py-2 px-3"
                        rows="4"
                        placeholder="What does your company do?"
                    ></textarea>
                    </div>
                    <div class="mb-4">
                    <label
                        for="contact_email"
                        class="block text-gray-700 font-bold mb-2"
                        >Contact Email</label
                    >
                    <input
                        v-model="form.company_contact_email"
                        type="email"
                        id="contact_email"
                        name="contact_email"
                        class="border rounded w-full py-2 px-3"
                        placeholder="Email address for applicants"
                        required
                    />
                    </div>
                    <div class="mb-4">
                    <label
                        for="contact_phone"
                        class="block text-gray-700 font-bold mb-2"
                        >Contact Phone</label
                    >
                    <input
                        v-model="form.company_contact_phone"
                        type="tel"
                        id="contact_phone"
                        name="contact_phone"
                        class="border rounded w-full py-2 px-3"
                        placeholder="Optional phone for applicants"
                    />
                    </div>
                    <div>
                    <button 
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full w-full focus:outline-none focus:shadow-outline"
                        type="submit"
                    >
                        Update  Job
                    </button>
                    </div>
                </form>
            </div>
      </div>
    </section>
</template>

<script setup>  
import axios from 'axios';
import router from '@/router';
import { reactive, onMounted} from 'vue';
import { useToast } from 'vue-toastification';
import { useRoute, useRouter } from 'vue-router';


const route = useRoute();
const jobId = route.params.id;

const form = reactive({
    type:  'Full-Time',
    title: '',
    description: '',
    salary: '',
    location: '',
    company_name: '',
    company_description: '',
    company_contact_email: '',
    company_contact_phone: '',
       
});

const state = reactive({
    job:{},
    isLoading:true,
});
const toast = useToast();


const handleSubmit = async() =>{
    const updatedJob = {
        title: form.title,
        type: form.type,
        location: form.location,
        description: form.description,
        salary:  form.salary,
        company: {
            name: form.company_name,
            description: form.company_description,
            contactEmail: form.company_contact_email,
            contactPhone: form.company_contact_phone,

        },

    }
   try{
        const response = await axios.put(`http://127.0.0.1:8000/api/posts/${jobId}`, updatedJob);
        // @todo -add toast
        toast.success('Job updated successfully');
        console.log(updatedJob);
        router.push(`/jobs/${response.data.data.id}`);
   }catch(error){
        console.error('Error while creating job', error);
        toast.error('Job was not updated');
   }
};



onMounted(async()=>{
    try{
        const response = await axios.get(`http://127.0.0.1:8000/api/posts/${jobId}`);
        Object.assign(form, response.data.data);
        
    }catch(error){
        console.error("Error fetching the job", error);
    }finally{
        state.isLoading = false;
    }
});
</script>