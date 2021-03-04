# Git Cheatsheet

## Initiate Git repository

Gitレポの初期化

initはinitiateの略称であって、初期化という意味

```command
$ git init
```

## Remote repository

最初のリモートレポは習慣的に`origin`と名付けられる  
originは始原という意味

リモートレポはグーグルドライブのようなクラウドの保存先  
レポはrepositoryの略称であって、倉庫という意味

### Pull from remote repository

リモートレポから落とす

```command
$ git pull <remote_name> <branch_name> --rebase

# e.g. git pull origin master
```

或いは、

```command
$ git pull <remote_name> <branch_name>

# e.g. git pull origin master
```

pullは元々引くという意味で、ここではクラウドから引き落とすという意味で使われている

gitの履歴を管理し読みやすくするためできるだけ`--rebase`を付けて使ってください

### Upload to remote repository

リモートレポに上げる

```command
$ git push <remote_name> <branch_name>

# e.g. git push origin master
```

pushは元々押すをいう意味で、ここでは倉庫に押し入るという意味で使われている

## Branch

branchは分岐という意味で、ここでは同じプロジェクトの中に別々の機能と実装するにぶつからないように分岐すること

### Start a new branch

新しい分岐を始める

```command
$ git branch <branch_name>

# e.g. git branch new_feature
```

或いは、

```command
$ git checkout -b <branch_name>

# e.g. git checkout -b new_feature
```

`checkout`を使うと直接分岐に入るのがこの二つに違い

分岐の名前は必ず単語、若しくは`_`で繋がった複数の単語にする

### Remove branch

分岐の削除

```command
$ git branch -D <branch_name>

# e.g. git branch -D new_feature
```

ローカルでの削除はpushの時に障害が発生するかもしれないし、落とす方にも障害が発生するかもしれないので、出来るだけ使わないでください

使うときは必ず予告し、同意を貰ってください

### Change branch

分機間の移動

```command
$ git checkout <branch_name>

# e.g. git checkout master
```

`checkout`の後に追う`branch_name`は移動先の分岐の名前

### Check branches

分岐をリストする

```command
$ git branch
```

或いは、

```command
$ git branch --list
```

`branch`機能はデフォルトで全分岐をリストすることになっている

## Daily

日常によく使うコマンド

### Stage

加わること、この動きはstagingとも言われる

`staging`は準備すること、ここではコミットに準備をする

```command
$ git add <file_name> [<file_name> ...]

# e.g. git add index.html
# e.g. git add index.html index.js
```

或いは、

```command
$ git add .
```

`add`の後に追うのは空白で区切るファイル名の列、若しくは`.`で全部

### Unstage

準備を取り消すこと

```command
$ git restore --stage <file_name>

# e.g. git restore --stage index.js
```

`restore`の機能に`--stage`のオプションを付けて、準備からおろすこと  
`restore`の後に追うのは空白で区切るファイル名の列  
`restore`はコミットされていないファイルを先回のコミットまで巻き戻すことはできるので、気を付けながら使ってください

### Status

statusは状態という意味で、そのまま使われている  
ステージングの状態だけでなく、反応できる限りgitの状態が見れる（pullなど時間のかかる動きで反応できない場合を除く）

```command
$ git status

# output
#
# On branch master
# Changes to be committed:
#   (use "git restore --staged <file>..." to unstage)
#         modified:   index.html
#
# Changes not staged for commit:
#   (use "git add <file>..." to update what will be committed)
#   (use "git restore <file>..." to discard changes in working directory)
#         modified:   index.js
```

上記通り、どの分岐にいるか、どんなファイルがステージングしたかしていないかも分かる

### Commit

`commit`は託す、認めるという意味で、ここではコードが自分が書いたということ認め、他人に託すということ かな?

```command
$ git commit [-a] -m <short_message> [-m <detail_message>]

# e.g. git commit -a -m "Style body of index page"
# e.g. git commit -am "Style body of index page"
# e.g. git commit -m "Style body of index page"
# e.g. git commit -m "Style body of index page" -m "reorder the display"
```

`-a`のオプションは`--all`の略、今した変更をコミットする。今までコミットしたことのないファイルはコミットされない

一つ目の`-m`のオプションは`--message`の略、今のコミットに備考を書く、そのタイトル。出来るだけ解りやすく、一行で済ましてください

二つ目の`-m`オプションも略であって、今のコミットの備考欄で、その内容。出来るだけコードを見なくてもどんな変更をしたかを解るように書いてください

### Show log in detail

コミット履歴を表示

```command
$ git log

# output
#
# commit 139c58c8ffcb8cd12980a17fd459fdf9c2351d45 (HEAD -> master, # origin/master)
# Author: THS95049 <ths95049@outlook.com>
# Date:   Tue Jul 21 11:58:11 2020 +0900
#
#     Prepare for code
#
# commit 39fd8d1cd5c84d3c14e3a9b638e5aeee386ca04d
# Author: ths95049 <66874876+ths95049@users.noreply.github.com>
# Date:   Tue Jul 7 21:25:10 2020 +0900
#
#     Document project
#
# commit 6bae2381644cd36dd5f39870b4652bbeefedb7de
# Author: ths95049 <66874876+ths95049@users.noreply.github.com>
# Date:   Tue Jul 7 21:19:31 2020 +0900
#
#     Initial commit
```

上記通り、書き込んだ人、時間などが見えるし、タイトルと詳細も見える

長くなるので、Vimの巡りショートカットを使っている  
下記の表は一番基本のキー

| キー  |  動き  |
| :---: | :----: |
|   j   |  下に  |
|   k   |  上に  |
|   q   | やめる |

### Show log in single line

```command
$ git log --oneline

# output
#
# 139c58c (HEAD -> master, origin/master) Prepare for code
# 39fd8d1 Document project
# 6bae238 Initial commit
```

全履歴を出すのは長すぎるので、短縮したコマンドは`--oneline`オプションを付けたもの  
ですが、コミットのIDのコミットハッシュと備考のタイトルしか見えない

### Revert

コミットした結果を巻き戻す

```command
$ git revert <commit_hash>

# e.g. git revert 139c58c
```

した変更に不満か、バグになったか、それ以外かに関わらず指定したコミットの内容をなかったことにする

そのため、できるだけコミットをして細かく意味あって分ける  
そうすることで、どこでバグが出たかも簡単に見つかるし、巻き戻せる  
例えば、一連の機能を実装するに、全部終わってから一気にではなく、機能ごとにコミットする
