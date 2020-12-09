import express from "express";
import nodemailer from "nodemailer";

import MailAccount from "./src/MailAccount";

// general config
const PORT = process.env.PORT || 8888;
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
  port: 587,
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
  let msg = {
    code: 0,
    info: null,
    err: null,
  };
  
  mailer
    .sendMail({
      from: sender.AccountString,
      to: new MailAccount("Manager", "manager@tokyo.hal.ac.jp").AccountString,
      text: "text body",
      html: "<html><body><p>HTML body</p></body></html>",
    })
    .then(
      (info) => {
        res.statusCode = 200;
        msg.code = 1;
        msg.info = info;
        res.send(msg);
      },
      (reason) => {
        res.statusCode = 500;
        msg.code = 0;
        msg.err = reason;
        res.send(msg);
      }
    )
    .catch((reason) => {
      res.statusCode = 500;
      msg.code = -1;
      msg.err = reason;
      res.send(msg);
    });
});
//#endregion

// ignite
server.listen(PORT, () => {
  console.log(`Server is now listening on port ${PORT}`);
});
