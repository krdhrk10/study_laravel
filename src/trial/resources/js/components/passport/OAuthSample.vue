<style scoped>
</style>

<template>
    <div>
        <div class="card card-default">
            <div class="card-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>
                        OAuth Sample
                    </span>
                </div>
            </div>

            <div class="card-body">
                <form role="form">
                    <!-- Grant Type -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Grant Type</label>

                        <div class="col-md-9">
                          <select id="begin-client-id" type="number" class="form-control"
								  v-model="beginForm.grantType">
							<template v-for="grant in grantTypes">
							  <option :value="grant.name">{{ grant.name }}</option>
							</template>
						  </select>
                        </div>
                    </div>

                    <!-- Client -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Client</label>

                        <div class="col-md-9">
                          <select id="begin-client-id" type="number" class="form-control"
								  v-model="beginForm.clientId">
							<template v-for="client in clients">
							  <option :value="client.id">{{ client.name }}</option>
							</template>
						  </select>
                        </div>
                    </div>

                    <!-- Client Id -->
                    <div v-show="isRequired('client_id')" class="form-group row">
                        <label class="col-md-3 col-form-label">Client ID</label>

                        <div class="col-md-9">
                            <input disabled type="text" class="form-control"
                                   :value="selectedClient.id">
                        </div>
                    </div>

                    <!-- Client Secret -->
                    <div v-show="isRequired('client_secret')" class="form-group row">
                        <label class="col-md-3 col-form-label">Client Secret</label>

                        <div class="col-md-9">
                            <input disabled type="text" class="form-control"
                                   :value="selectedClient.secret">
                        </div>
                    </div>

                    <!-- Username -->
                    <div v-show="isRequired('username')" class="form-group row">
                        <label class="col-md-3 col-form-label">Username</label>

                        <div class="col-md-9">
                            <input type="text" class="form-control"
                                   v-model="beginForm.username">
                        </div>
                    </div>

                    <!-- Password -->
                    <div v-show="isRequired('password')" class="form-group row">
                        <label class="col-md-3 col-form-label">Password</label>

                        <div class="col-md-9">
                            <input type="password" class="form-control"
                                   v-model="beginForm.password">
                        </div>
                    </div>

                    <!-- Redirect URL -->
                    <div v-show="isRequired('redirect_url')" class="form-group row">
                        <label class="col-md-3 col-form-label">Redirect URL</label>

                        <div class="col-md-9">
                            <input disabled type="text" class="form-control"
                                   :value="selectedClient.redirect">
                        </div>
                    </div>

                    <!-- Response Type -->
                    <div v-show="isRequired('response_type')" class="form-group row">
                        <label class="col-md-3 col-form-label">Response Type</label>

                        <div class="col-md-9">
                            <input disabled type="text" class="form-control" name="responseType"
                                   :value="selectedResponseType">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Authorize Endpoint</label>

                        <div class="col-md-9">
                          <span class="form-text text-muted">{{ authorizeEndpoint }}</span>
						  <input :disable="selectedClient.id == 0"
								 class="form-group" type="button" @click="beginAuth" value="Begin"></input>
                        </div>
                    </div>
                </form>

            </div>
        </div>


    </div>
</template>

<script>
  import { getClientCandidates } from './clients.js';
    export default {
        data() {
            return {
				grantTypes: [
					{
						name: "authorization_code",
						props: ["client_id", "redirect_url", "response_type", "scope"],
					},
					{
						name: "password",
						props: ["client_id", "client_secret", "username", "password", "scope"],
					},
					{
						name: "client_credentials",
						props: ["client_id", "client_secret", "scope"],
					},
					{
						name: "implicit",
						props: ["client_id", "redirect_url", "response_type", "scope"],
					},
				],
                beginForm: {
					gratnType: 'authorization_code',
					clientId: 0,
					scope: '',
					username: '',
					password: '',
                },

            };
        },

		computed: {
			clients() {
				if (this.selectedGrant.name=="password") {
					return getClientCandidates("password");
				}
				if (this.selectedGrant.name=="personal") {
					return getClientCandidates("personal");
				}
				return getClientCandidates("user");
			},

			authorizeEndpoint() {
				return 'http://localhost:8080/oauth/authorize?' + this.queryParams(false);
			},

			beginURI() {
				return '/passport/begin?' + this.queryParams(true);
			},

			selectedGrant() {
				if (this.beginForm.grantType) {
					let grant = this.targetGrant(this.beginForm.grantType);
					if (grant) {
						return grant;
					}
				}
				return {
					name: "",
					props: [],
				};
			},

			selectedClient() {
				if (0 < this.beginForm.clientId) {
					let client = this.targetClient(this.beginForm.clientId);
					if (client) {
						return client;
					}
				}
				return {
					id: 0,
					redirect: '',
					secret: '',
				};
			},

			selectedResponseType() {
				if (this.beginForm.grantType == "authorization_code") {
					return "code";
				}
				if (this.beginForm.grantType == "implicit") {
					return "token";
				}
				return "";
			}
		},

        methods: {
			queryParams(withGrantType) {
				let p = {
					client_id: this.selectedClient.id,
					scope: this.beginForm.scope,
				};

				if (withGrantType) {
					p.grant_type = this.beginForm.grantType;
				}

				if (this.isRequired('client_secret')) {
					p.client_secret = this.selectedClient.secret;
				}
				if (this.isRequired('redirect_url')) {
					p.redirect_url = this.selectedClient.redirect;
				}
				if (this.isRequired('username')) {
					p.username = this.beginForm.username;
				}
				if (this.isRequired('password')) {
					p.password = this.beginForm.password;
				}
				if (this.isRequired('response_type')) {
					p.response_type = this.selectedResponseType;
				}

				const param = new URLSearchParams(p);
				return param.toString();
			},

			targetGrant(grantType) {
				for (let grant of this.grantTypes) {
					if (grant.name == grantType) {
						return grant;
					}
				}
				return null;
			},

			targetClient(clientId) {
				for (let client of this.clients) {
					if (client.id == clientId) {
						return client;
					}
				}
				return null;
			},

			isRequired(prop) {
				return 0 <= this.selectedGrant.props.indexOf(prop);
			},

			beginAuth() {
				if (this.beginForm.grantType == 'implicit') {
					axios.get(this.beginURI).then(resp => {
						alert(JSON.stringify(resp.data, 2));
					});
				} else {
					window.location.href = this.beginURI;
				}
			},
        },

		mounted() {
		}
    }
</script>
