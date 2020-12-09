import express from "express";
import nodemailer, { TestAccount } from "nodemailer";

import MailAccount from "./MailAccount";

// general config
const PORT = 80;
let server = express();

//#region  generic auth service
server.get("/auth/", (req, res) => {
  let respond = {
    status: 200,
    body: "Auth Service",
  };

  res.header("Content-Type", "application/json");
  res.send(JSON.stringify(respond));
});
//#endregion

//#region mailing service
const mailer = nodemailer.createTransport({
  // dev env with MailTrap
  host: "smtp.mailtrap.io",
  port: 587 ,
  auth: {
    user: "142de77bed440f",
    pass: "a12f9f2c00546c",
  },
  pool: true,
  maxConnections: 1,
});

const sender: MailAccount = new MailAccount(
  "IH12A092 Group 5",
  "service@group5-ih12a092.com"
);

server.post("/mail/", (req, res) => {
  mailer
    .sendMail({
      from: sender.AccountString,
      to: new MailAccount("Manager", "manager@tokyo.hal.ac.jp").AccountString,
      text: "text body",
      html: "<html><body><p>HTML body</p></body></html>",
    })
    .then(
      (info) => {
        console.log(`Mail sent Successfully, with sending info:`);
        console.log(JSON.stringify(info, null, 2));
      },
      (reason) => {
        console.warn(`Mail sent Failingly, with reason:`);
        console.log(JSON.stringify(reason, null, 2));
      }
    )
    .catch((reason) => {
      console.error(`Mail sent Failingly, with error:`);
      console.log(JSON.stringify(reason, null, 2));
    });

  res.statusCode = 200;
  res.send();
});
//#endregion

// ignite
server.listen(PORT, () => {
  console.log(`Server is now listening on port ${PORT}`);
});
