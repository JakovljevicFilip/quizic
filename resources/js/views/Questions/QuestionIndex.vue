<template>
    <div class="questions">
        <!-- <div class="loading" v-if="loading">
            Loading...
        </div>

        <div v-if="error" class="error">
            {{ error }}
        </div>

        <ul>
            <li v-for="{text, answers} in questions">
                <strong>Text:</strong> {{ text }}
                <ul>
                   <li v-for="{text} in answers">
                       <strong>Text:</strong> {{ text }}
                   </li>
                </ul>
            </li>
        </ul> -->
    </div>
</template>
<script>
// import axios from 'axios';
// export default {
//     data() {
//         return {
//             loading: false,
//             questions: null,
//             error: null,
//         };
//     },
//     created() {
//         this.fetchData();
//     },
//     methods: {
//         fetchData() {
//             this.error = this.questions = null;
//             this.loading = true;
//             axios
//                 .get('/api/question')
//                 .then(response => {
//                     this.loading=false;
//                     this.questions=response.data.questions;
//                 })
//                 .catch(error=>{
//                     console.log(error);
//                     this.loading=false;
//                     this.error = error.response.data.message || error.message;
//                 });
//         }
//     }
// }
</script>
