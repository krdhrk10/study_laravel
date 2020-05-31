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
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Token Type</label>

                        <div class="col-md-9">
                          <input disable id="token-type" type="text" class="form-control"
								  v-model="token.token_type">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Access Token</label>

                        <div class="col-md-9">
                          <textarea disabled class="form-control"
									v-model="token.access_token"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Refresh Token</label>

                        <div class="col-md-9">
                          <textarea disabled type="text" class="form-control"
									v-model="token.refresh_token"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Expires In</label>

                        <div class="col-md-9">
                            <input disabled type="text" class="form-control"
                                   v-model="token.expires_in">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label"></label>

                        <div class="col-md-9">
						  <input :disable="!token.refresh_token"
								 class="form-group" type="button" @click="getAccessToken('refresh_token')" value="Refresh"></input>
                        </div>
                    </div>
                </form>

            </div>
        </div>


    </div>
</template>

<script>
  import { getClient } from './clients.js';
    export default {
        props: {
			clientId: {
				type: Number,
				default: 0,
				required: true,
			},
			grantType: {
				type: String,
				default: '',
				required: true,
			},
			code: {
				type: String,
				default: '',
				required: false,
			},
			username: {
				type: String,
				default: '',
				required: false,
			},
			password: {
				type: String,
				default: '',
				required: false,
			},
			clientSecret: {
				type: String,
				default: '',
				required: false,
			},
			scope: {
				type: String,
				default: '',
				required: false,
			},
			accessToken: {
				type: String,
				default: '',
				required: false,
			},
			tokenType: {
				type: String,
				default: '',
				required: false,
			},
			expiresIn: {
				type: String,
				default: '',
				required: false,
			},
        },

        data() {
            return {
				clients: [],
                token: {
                    access_token: '',
					expires_in: 0,
					refresh_token: '',
					token_type: '',
                },

            };
        },

		computed: {
			targetClient() {
				return getClient(this.clientId);
			},
		},

        methods: {
            getClients(callback) {
                axios.get('/oauth/clients').then(resp => {
                    this.clients = resp.data;
					callback();
                });
            },

			getAccessToken(grantType) {
				const self = this;
				let request = {
					client_id: self.clientId,
				};
				if (grantType == 'authorization_code') {
					request.grant_type = 'authorization_code';
					request.client_secret = self.targetClient.secret;
					request.redirect_url = self.targetClient.redirect;
					request.code = self.code;
				} else if(grantType == 'password') {
					request.grant_type = 'password';
					request.client_secret = self.clientSecret;
					request.username = self.username;
					request.password = self.password;
					request.scope = self.scope;
				} else if(grantType == 'client_credentials') {
					request.grant_type = 'client_credentials';
					request.client_secret = self.clientSecret;
					request.scope = self.scope;
				} else if(grantType == 'refresh_token') {
					request.grant_type = 'refresh_token';
					request.client_secret = self.targetClient.secret;
					request.refresh_token = self.token.refresh_token;
					request.scope = self.scope;
				} else if(grantType == 'implicit') {
					self.token.access_token = self.accessToken;
					self.token.expires_in = self.expiresIn;
					self.token.token_type = self.tokenType;
				} else {
					return;
				}
				axios.post('http://localhost:8080/oauth/token', request).then(resp => {
					console.log('resp.data:' + JSON.stringify(resp.data));
					console.log('resp.status:' + resp.status);
					self.token = resp.data;
				});
			},
        },

		mounted() {
			const self = this;
			self.getClients(() => {
				self.getAccessToken(self.grantType);
			});
			
		}
    }
</script>
