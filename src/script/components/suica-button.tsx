import React from "react";

import { session as ReadIdm, sleep } from "../model/felica";

interface SuicaButtonProps {
  updateIdm(idm: string): void;
  /**
   * Handler for suica reading error.
   * @param code code of error. negative for set up error; 0 for read error; positive for device connection close error.
   * @param message error message.
   */
  errorHandler(code: number, message?: string): void;
  updateMessage(msg: string, warn: boolean): void;
}

interface SuicaButtonState {
  /**
   * Where is in reading process.
   */
  isReading: boolean;
}

export default class SuicaButton extends React.Component<
  SuicaButtonProps,
  SuicaButtonState
> {
  /**
   * Non-state object, as usb nfc card reader.
   */
  reader: any;
  /**
   * Interval of card reading, in millisecond.
   */
  readInterval: number = 500;

  constructor(props: SuicaButtonProps) {
    super(props);

    this.state = {
      isReading: false,
    };
  }

  readSuica = async () => {
    this.props.updateMessage("", false);

    this.setState({
      isReading: true,
    });

    // pair to device
    try {
      if (!this.reader) {
        this.reader = await (navigator as any).usb.requestDevice({
          filters: [{}],
        });

        await this.reader.open();
        await this.reader.selectConfiguration(1);
        await this.reader.claimInterface(0);
      }
    } catch (ex: any) {
      this.props.errorHandler(-1, ex.message);
    }

    // actual read of felica card
    try {
      let code;

      do {
        code = await ReadIdm(this.reader);

        if (code) {
          this.props.updateIdm(code);
        }

        await sleep(this.readInterval);
      } while (code == "");
    } catch (ex) {
      this.props.errorHandler(0);

      // close device on error
      try {
        this.reader.close();
        this.reader = null;
      } catch (ex) {
        this.props.errorHandler(1);
      } finally {
        this.reader = null;
      }
    }

    this.setState({
      isReading: false,
    });
  };

  render() {
    return this.state.isReading ? (
      <button disabled>Reading Suica Card ...</button>
    ) : (
      <button onClick={this.readSuica}>Click to Read suica card</button>
    );
  }
}
