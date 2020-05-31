const __clients = [
	{
		id: 3,
		name: "passport_oatuh_client",
		secret: "XXXX",
		redirect: "http://localhost:8080/passport/callback",
		personal: false,
		password: false,
	},
	{
		id: 2,
		name: "passport_passwd_client",
		secret: "XXXX",
		redirect: "",
		personal: false,
		password: true,
	},
	{
		id: 1,
		name: "passport_personal_client",
		secret: "XXXX",
		redirect: "",
		personal: true,
		password: false,
	},
];

export function getClientCandidates(type) {
	let c = [];
	for (let cli of __clients) {
		if (type=='password' && cli.password) {
			c.push(cli);
		}
		if (type=='personal' && cli.personal) {
			c.push(cli);
		}
		if (type=='user' && !cli.personal && !cli.password) {
			c.push(cli);
		}
	}
	return c;
}

export function getClient(id) {
	for (let cli of __clients) {
		if (cli.id == id) {
			return cli;
		}
	}
	return null;
}
