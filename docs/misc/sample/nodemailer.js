"use strict";
backup.ts;
let body = { text: "", html: "" };
try {
    body = JSON.parse(req.body);
}
catch (ex) {
    res.send(JSON.stringify({
        err: "Inappropriate request body",
    }));
}
mailer.sendMail({
    from: sender.AccountString,
    to: new MailAccount("Manager", "noddychiu@gmail.com").AccountString,
    text: body.text,
    html: body.html,
});
var mailer = nodemailer.createTransport({
    host: "smtp.office365.com",
    port: 587,
    auth: {
        user: "ths95049@outlook.com",
        pass: "ths95049",
    },
});
let testAccount = await nodemailer.createTestAccount();
// create reusable transporter object using the default SMTP transport
let transporter = nodemailer.createTransport({
    host: "smtp.ethereal.email",
    port: 587,
    secure: false,
    auth: {
        user: testAccount.user,
        pass: testAccount.pass,
    },
});
