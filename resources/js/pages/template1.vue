<template>
    <div v-if="loading === true">
        <Loader />
    </div>
    <div v-else>
        <div  class="text-end my-3">
            <div v-if="downloadProcessedFileAccess" @click="downloadProcessedFile" class="btn linked-icon">
                <font-awesome-icon icon="download" fixed-width /> File
            </div>
        </div>
        <form @submit.prevent="submitForm" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="prompt" class="form-label">{{ ('Prompt') }}</label>
                <input v-model="formData.prompt" type="text" class="form-control" id="prompt" required autofocus>
            </div>

            <div class="mb-3">
                <label for="csvFile" class="form-label">{{ ('CSV File') }}</label>
                <input type="file" class="form-control" id="csvFile" @change="handleFileChange" accept=".csv">
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">{{ ('RUN') }}</button>
            </div>
        </form>

    </div>
</template>

<script>
    export default {
        name:"template1",
        data() {
            return {
                formData: new this.$form({
                    prompt: '',
                    csvFile: null,
                }),
                loading: false,
                downloadProcessedFileAccess: false
            };
        },
        methods: {
            async submitForm() {
                this.loading = true;
                try {
                    const { data } = await this.formData.post('/api/send-to-openai')
                        this.$toast.success( 'Data processed successfully.',{ position: 'top-right', duration: 3000 })
                        // Call the download endpoint for the processed file
                        this.downloadProcessedFileAccess = true;
                } catch (e) {
                    handleError(e,this.$toast);
                    // window.location.reload();
                }
                this.loading = false;
                this.formData.prompt = '';
                // this.formData.csvFile = null;
            },
            handleFileChange(event) {
                // Update formData.csvFile when a file is selected
                this.formData.csvFile = event.target.files[0];
            },
            async downloadProcessedFile() {
                try {
                    // Adjust the API endpoint URL
                    const response = await this.$axios.get('/api/downloadProcessedFile', {
                        responseType: 'blob', // Set the response type to 'blob' for file downloads
                    });

                    // Create a Blob from the response data
                    const blob = new Blob([response.data]);

                    // Create a link element to trigger the download
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'processed_file.csv';

                    // Trigger the download
                    link.click();

                } catch (error) {
                    console.error('Error downloading processed file:', error);
                }
            },
        },
    };
</script>

<style>
    /* Add your custom styles here */
</style>
