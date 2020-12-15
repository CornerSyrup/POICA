import React from "react";
import ReactDOM from "react-dom";

import Suica from "./components/setting/suica";
import Pass from "./components/setting/password";

import "../page/setting.pug";
import "../style/setting.less";

interface SettingPageProps {}

interface SettingPageState {}

class SettingPage extends React.Component<SettingPageProps, SettingPageState> {
  constructor(props: SettingPageProps) {
    super(props);
  }

  render() {
    return (
      <section>
        <Pass />
        <Suica />
      </section>
    );
  }
}

ReactDOM.render(<SettingPage />, document.querySelector("body>main"));
