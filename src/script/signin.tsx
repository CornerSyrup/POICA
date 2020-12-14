import React from "react";
import ReactDOM from "react-dom";

import SignInForm, { SignInFormData } from "./components/signin/signin-form";
import SignInSuica from "./components/signin/signin-suica";
import { SignInRespond } from "./model/Respond";
import { session as ReadIdm, sleep } from "./model/felica";

import "../page/signin.pug";
import "../style/signin.less";

export interface StatusMessage {
  message: string;
  warning: boolean;
}

interface SignInProps {}

interface SignInState {
  userID: string;
  password: string;
  felicaIdm: string;
  /**
   * Status of client side sign in process. E.g. "Signing in ..."
   */
  status: StatusMessage;
  suicaStatus: number;
}

class SignInPage extends React.Component<SignInProps, SignInState> {
  /**
   * Non-state object, as usb nfc card reader.
   */
  readerDevice: any;
  /**
   * Interval of card reading, in millisecond.
   */
  readInterval: number = 500;
  constructor(props: SignInProps) {
    super(props);

    this.state = {
      userID: "",
      password: "",
      felicaIdm: "",
      status: {
        message: "",
        warning: false,
      },
      suicaStatus: -1,
    };
  }

  messageUpdate = (msg: StatusMessage) => {
    this.setState({
      status: {
        message: msg.message,
        warning: msg.warning,
      },
    });
  };

  formUpdate = (data: SignInFormData) => {
    this.setState({
      userID: data.user ?? this.state.userID ?? "",
      password: data.pass ?? this.state.password ?? "",
    });
  };

  formSignIn = () => {
    this.setState({
      status: { message: "Signing in ...", warning: false },
    });

    fetch("/signin/", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        sid: this.state.userID,
        pwd: this.state.password,
      }),
    })
      .then((respond) => {
        this.setState({
          password: "",
        });
        return respond.json();
      })
      .then((result: SignInRespond) => {
        if (result.status) {
          window.location.href = "/";
        } else {
          if (result.error) {
            this.setState({
              status: {
                message: result.error?.message as string,
                warning: true,
              },
            });
          } else {
            this.setState({
              status: { message: "Password incorrect", warning: false },
            });
          }
        }
      })
      .catch((reason) => {
        // TODO: store error to innoDB and sync to server later
        console.error(reason);
      });
  };

  suicaSignIn = async () => {
    // pair to device
    try {
      if (!this.readerDevice) {
        this.readerDevice = await (navigator as any).usb.requestDevice({
          filters: [{}],
        });

        await this.readerDevice.open();
        await this.readerDevice.selectConfiguration(1);
        await this.readerDevice.claimInterface(0);
      }
    } catch (ex) {
      // todo: add connect error handler
      console.error(ex);
    }

    // actual read of felica card
    try {
      let idm;

      do {
        idm = await ReadIdm(this.readerDevice);

        if (idm) {
          this.setState({
            felicaIdm: idm,
          });
        }

        await sleep(this.readInterval);
      } while (idm == "");
    } catch (ex) {
      // todo: add read error handler
      console.error(ex);

      // close device on error
      try {
        this.readerDevice.close();
        this.readerDevice = null;
      } catch (ex) {
        // todo: add close error handler
        console.error(ex);
      } finally {
        this.readerDevice = null;
      }
    }

    if (!this.state.felicaIdm) {
      return;
    }

    this.setState({
      suicaStatus: 0,
    });

    fetch("/signin/suica/", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        sid: this.state.userID,
        idm: this.state.felicaIdm,
      }),
    })
      .then((respond) => respond.json())
      .then((result: SignInRespond) => {
        if (result.status) {
          window.location.href = "/";
          this.setState({
            felicaIdm: "",
            suicaStatus: 1,
          });
        } else {
          this.setState({
            status: { message: result.error?.message as string, warning: true },
            felicaIdm: "",
            suicaStatus: -1,
          });
        }
      });
  };

  render() {
    return (
      <div>
        <SignInForm
          user={this.state.userID}
          pass={this.state.password}
          formDataUpdate={this.formUpdate}
          formConfirm={this.formSignIn}
          updateMessage={this.messageUpdate}
        />
        {this.state.status.message && (
          <p
            className={this.state.status.warning ? "warning" : ""}
            dangerouslySetInnerHTML={{ __html: this.state.status.message }}
          ></p>
        )}
        <hr />
        <SignInSuica state={this.state.suicaStatus} signIn={this.suicaSignIn} />
        <br />
        <button disabled>Sign In with Face Recognition</button>
      </div>
    );
  }
}

ReactDOM.render(<SignInPage />, document.querySelector("body>main"));
